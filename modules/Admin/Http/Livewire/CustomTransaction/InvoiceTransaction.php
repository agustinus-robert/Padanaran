<?php

namespace App\Livewire\CustomTransaction;

use App\Models\extraItems;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\TransRoom;
use App\Models\TransRoomPerson;
use App\Models\TransRoomRooms;
use App\Models\TransExtras;
use App\Models\Bed;
use App\Models\Bed_Facilities;
use Illuminate\Support\Facades\Http;

use DB;
use Livewire\Component;

class InvoiceTransaction extends Component
{

    public $extra = [];
    public $bed = [];
    public $bed_facility = [];

    public $invoice_to_api = [];
    public $room_to_api = [];
    public $extra_to_api = [];


    public $transExtra;
    public $transPerson;
    public $transRoom;
    public $transExtras;
    public $invoice;
    public $langs;

    public $name_booking;
    public $email;
    public $ktp;
    public $phone;

    public $lang;
    public $trans_id;
    public $check_person;


    public function mount(){
        $session_id = session()->getId();
        $this->trans_id = request()->invoice_id;
        $this->langs = request()->bahasa;
        $tr_room = TransRoom::where('session_id', $session_id)->get()->first();
        $this->check_person = TransRoomPerson::where('session_id', $session_id)->get()->first();

        $response_facility_type = Http::withHeaders(
        [
            "X-API-KEY" => "nyyEjH2EA6haC2VJ3Dp9QmsJ5GdnyKN52oj+kNxaXW0=",
            "Accept" => "application/json",
        ])->get("https://emp.digipemad.com/api/booking/hotel-facilities");

        if($response_facility_type->getStatusCode() == 200){
            $this->extra = json_decode($response_facility_type->getBody()->getContents(), true)['data'];
        } else {
            $this->extra = extraItems::get();
        }

         $response_room_type = Http::withHeaders(
        [
            "X-API-KEY" => "nyyEjH2EA6haC2VJ3Dp9QmsJ5GdnyKN52oj+kNxaXW0=",
            "Accept" => "application/json",
        ])->get("https://emp.digipemad.com/api/booking/hotel-room-types?start_at=".date("Y-m-d", strtotime($tr_room->date_in))."&end_at=".date('Y-m-d', strtotime($tr_room->date_out)));

        if($response_room_type->getStatusCode() == 200){
            $this->bed = json_decode($response_room_type->getBody()->getContents(), true)['data'];

            $temp_bed = $this->bed;

            foreach($temp_bed as $key => $value){

                foreach($value['facilities'] as $key2 => $value2){
                    $this->bed_facility[$value['id']][] = [
                        'id' => $value2['id'],
                        'bed_id' => $value['id'],
                        'title' => $value2['name'],
                        'icon' => $value2['icon']
                    ];
                }
            }
        } else {
            $this->bed = Bed::all();

            $temp_facility = Bed_Facilities::all();



            foreach($temp_facility as $key => $val){
                $this->bed_facility[$val->bed_id][] = [
                    'id' => $val->id,
                    'bed_id' => $val->bed_id,
                    'title' => $val->title,
                    'icon' => $val->icon
                ];
            }
        }

        $transExta = TransRoom::where('session_id', $session_id)->get();
        $transPersn = TransRoomPerson::where('session_id', $session_id)->get();
        $transRom = TransRoomRooms::where('session_id', $session_id)->get();
        $transExtrs = TransExtras::where('session_id', $session_id)->get();

         $this->invoice_to_api = $transExta->first();
         $this->room_to_api = $transRom;
         $this->extra_to_api = $transExtrs;

        foreach($transPersn as $key => $val){
            $this->transPerson[$val->person] = $val;
        }

        foreach($transRom as $key => $val){
            $this->transRoom[$val->room_id] = $val;
        }

        foreach($transExtrs as $key => $val){
            $this->transExtras[$val->id_extras] = $val;
        }
    }

    public function check_mails($email){
        if(isset(TransRoomPerson::where('email', $email)->get()->first()->email)){
            return true;
        } else {
            return false;
        }
    }

    public function printInv(){
        if(count(array_filter(str_split($this->ktp),'is_numeric')) < 16 || count(array_filter(str_split($this->ktp),'is_numeric')) > 16){
            $this->alert('warning', 'Nomor KTP Harus berjumlah 16 karakter');
        } else if(isset($this->check_person->identity) && $this->check_person->identity == $this->ktp){
            $this->alert('warning', 'Nomor KTP sudah pernah dimasukkan, harap masukkan nomor KTP yang lain');
        } else if($this->check_mails($this->email) == true){
            $this->alert('warning', 'Email sudah pernah dimasukkan, harap masukkan email yang lain');
        } else if(empty($this->name_booking) && !is_string($this->name_booking)){
            $this->alert('warning', 'Nama Identitas booking harus diisi dan bertipe huruf');
        }else if(empty($this->email) && !is_string($this->email)){
            $this->alert('warning', 'Email harus diisi');
        } else if(empty($this->ktp) && !is_numeric($this->ktp)){
            $this->alert('warning', 'Nomor KTP harus diisi dan harus berisi angka');
        } else if(empty($this->phone) && !is_numeric($this->phone)){
            $this->alert('warning', 'Nomor handphone harus diisi dan harus berisi angka');
        } else {

            $session_id = session()->getId();

             $response_data = Http::withHeaders(
            [
                "X-API-KEY" => "nyyEjH2EA6haC2VJ3Dp9QmsJ5GdnyKN52oj+kNxaXW0=",
                "Content-Type" => "application/json",
            ])->post("https://emp.digipemad.com/api/account/customers", [
                'name' => $this->name_booking,
                'email_address' => $this->email,
                'id_number' => $this->ktp,
                'phone' => $this->phone
            ]);

            $json_dec = json_decode($response_data, true);
            $rands = rand();

            if(isset($json_dec['customer'])){
            $arr = [];
            $arr['customer_id'] = $json_dec['customer']['id'];
            $arr['kd'] = $rands;

            foreach($this->room_to_api as $key => $val){
                $arr['items'][] = [
                    'kd' => $rands,
                    'hotel_room_type_id' => $val->room_id,
                    'start_at' => $this->invoice_to_api->date_in,
                    'end_at' => $this->invoice_to_api->date_out,
                    'quantity' => 1
                ];
            }

            foreach($this->extra_to_api as $key => $val){
                $arr['items'][] = [
                    'kd' => $rands,
                    'hotel_room_type_id' => $val->id_extras,
                    'start_at' => $this->invoice_to_api->date_in,
                    'end_at' => $this->invoice_to_api->date_out,
                    'quantity' => 1
                ];
            }

              $response_data_masuk = Http::withHeaders(
            [
                "X-API-KEY" => "nyyEjH2EA6haC2VJ3Dp9QmsJ5GdnyKN52oj+kNxaXW0=",
                "Content-Type" => "application/json",
            ])->post("https://emp.digipemad.com/api/booking/bookings", $arr);


          } else {
            dd($json_dec);
          }




            DB::beginTransaction();

            try {
            TransRoomPerson::where('session_id', $session_id)->delete();


                //$arr['customer_id'] = $resonse_data->id;



                // $response_data = Http::withHeaders(
                    // [
                    //     "X-API-KEY" => "nyyEjH2EA6haC2VJ3Dp9QmsJ5GdnyKN52oj+kNxaXW0=",
                    //     "Content-Type" => "application/json",
                    // ])->post("https://emp.digipemad.com/api/account/customers", [
                    //     'name' => $this->name_booking,
                    //     'email_address' => $this->email,
                    //     'google_id' => "1234567890",
                    //     'id_number' => $this->ktp,
                    //     'phone' => $this->phone
                    // ]);


                $roomPerson = new TransRoomPerson();

                $roomPerson->session_id = $session_id;
                $roomPerson->name = $this->name_booking;
                $roomPerson->identity = $this->ktp;
                $roomPerson->phone = $this->phone;
                $roomPerson->email = $this->email;

                $roomPerson->save();




                DB::commit();


                redirect(url($this->langs.'/room_invoice_print/'.$this->trans_id));
            } catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }

        }


    }

    public function render()
    {
        return view('livewire.custom-transaction.invoice-transaction')
        ->extends('layouts.front_end.index')
        ->section('content');
    }
}
