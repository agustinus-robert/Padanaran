<?php

namespace App\Livewire\CustomTransaction;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use App\Models\FacilityTransaction;
use App\Models\TransExtras;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use DB;

class FacilityForm extends Component
{

    public $resto = [];
    public $in_date;
    public $out_date;
    public $langs;

    public $name_booking;
    public $email;
    public $ktp;
    public $phone;

    public $checkbox = [];
    public $capacitys = [];
    public $count_save = 0;

    public function mount(){
        $this->langs = request()->bahasa;
    }

    #[On('cdate')]
    public function change_date(){
         $response_facility_resto = Http::withHeaders(
        [
            "X-API-KEY" => "nyyEjH2EA6haC2VJ3Dp9QmsJ5GdnyKN52oj+kNxaXW0=",
            "Accept" => "application/json",
        ])->get("https://emp.digipemad.com/api/booking/restaurant-tables?start_at=".date('Y-m-d', strtotime($this->in_date)).'&end_at='.date('Y-m-d', strtotime($this->out_date)));


        if($response_facility_resto->getStatusCode() == 200){
            if(date('Y-m-d', strtotime($this->out_date)) > date('Y-m-d', strtotime($this->in_date))){
                $this->checkbox = [];
                $this->resto = json_decode($response_facility_resto->getBody()->getContents(), true)['data'];
                $this->dispatch('contentChanged');
            } else {
                $this->resto = [];
            }
        }
    }

    public function selectedCheckbox($id){
        dd($id);
    }

    public function check_mails($email){
        if(isset(FacilityTransaction::where('email', $email)->get()->first()->email)){
            return true;
        } else {
            return false;
        }
    }

    public function check_ktp($ktp){
        if(isset(FacilityTransaction::where('identity', $ktp)->get()->first()->identity)){
            return true;
        } else {
            return false;
        }
    }

    public function save(){

        $run = '';
        if(count(array_filter(str_split($this->ktp),'is_numeric')) < 16 || count(array_filter(str_split($this->ktp),'is_numeric')) > 16){
            $this->alert('warning', 'Nomor KTP Harus berjumlah 16 karakter');
        } else if($this->check_ktp($this->ktp) == true){
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

            foreach($this->checkbox as $key => $val){
                foreach($val as $key2 => $val2){
                    $arr['items'][] = [
                        'kd' => rand(),
                        'restaurant_table_id' => $key,
                        'start_at' => date('Y-m-d', strtotime($this->in_date)),
                        'end_at' => date('Y-m-d', strtotime($this->out_date)),
                        'quantity' => $key2
                    ];
                }
            }

            $run = 1;
            //   $response_data_masuk = Http::withHeaders(
            // [
            //     "X-API-KEY" => "nyyEjH2EA6haC2VJ3Dp9QmsJ5GdnyKN52oj+kNxaXW0=",
            //     "Content-Type" => "application/json",
            // ])->post("https://emp.digipemad.com/api/booking/bookings", $arr);

            //     if($response_data_masuk->getStatusCode() == 201){
            //         $run = 1;
            //     } else {
            //         dd($response_data_masuk);
            //     }
          } else {
            dd($json_dec);
          }


           if($run == 1){

                DB::beginTransaction();

                try {
                $facilityPerson = new FacilityTransaction();

                $facilityPerson->name = $this->name_booking;
                $facilityPerson->identity = $this->ktp;
                $facilityPerson->phone = $this->phone;
                $facilityPerson->email = $this->email;
                $facilityPerson->date_in = date('Y-m-d', strtotime($this->in_date));
                $facilityPerson->date_out = date('Y-m-d', strtotime($this->out_date));

                $facilityPerson->save();

                foreach($arr['items'] as $key3 => $val3){
                    $facilityExtras = new TransExtras();
                    $facilityExtras->session_id = $facilityPerson->id;
                    $facilityExtras->id_extras = $val3['restaurant_table_id'];
                    $facilityExtras->save();
                }

                DB::commit();
                redirect(url($this->langs.'/facility_invoice_print/'.$facilityPerson->id));

                } catch (\Exception $e) {
                    DB::rollback();
                    dd($e);
                }
            } else {
                dd('transaksi gagal');
            }

        }
    }

    public function checkbox_mark(){
         if(count($this->checkbox) > 0){
             $this->count_save = 1;
         } else {
             $this->count_save = 0;
         }

         return $this->count_save;
        //$this->dispatch('contentChanged');
    }

    public function render()
    {
        return view('livewire.custom-transaction.facility-form');
    }
}
