<?php

namespace App\Livewire\CustomTransaction;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Http\Request;
use App\Models\TransRoom;
use App\Models\TransRoomPerson;
use App\Models\TransRoomRooms;
use App\Models\Bed;
use App\Models\Bed_Facilities;
use DB;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;


class RoomTransaction extends Component
{


    public $bed = [];

    public $bed_facility = [];
    public $bed_show_room = [];

    public $customer = [];
    public $room = [];
    public $cek_available = [];
    public $person_c = '';
    public $person_r = [];
    public $cek_trans = '';

    public $completed = '';
    public $invoice_id = '';
    public $people;
    public $cs_id = '';
    public $langs = '';

    public function mount(){
        $session_id = session()->getId();
        $tr_room = TransRoom::where('session_id', $session_id)->get()->first();
        $this->langs = request()->bahasa;


        $this->cs_id = request()->customer_id;




        $response_room_type = Http::withHeaders(
        [
            "X-API-KEY" => "nyyEjH2EA6haC2VJ3Dp9QmsJ5GdnyKN52oj+kNxaXW0=",
            "Accept" => "application/json",
        ])->get("https://emp.digipemad.com/api/booking/hotel-room-types?start_at=".date("Y-m-d", strtotime($tr_room->date_in))."&end_at=".date('Y-m-d', strtotime($tr_room->date_out)));

        if($response_room_type->getStatusCode() == 200){
            $this->bed = json_decode($response_room_type->getBody()->getContents(), false)->data;

            $temp_bed = $this->bed;

            foreach($temp_bed as $key => $value){

                foreach($value->facilities as $key2 => $value2){
                    $this->bed_facility[$value->id][] = [
                        'id' => $value2->id,
                        'bed_id' => $value->id,
                        'title' => $value2->name,
                        'icon' => $value2->icon
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




        $this->customer = TransRoomRooms::where('session_id', $session_id)->get();
        $temp_room = TransRoomRooms::where('session_id', $session_id)->get();


        $this->cek_available = TransRoomRooms::where('session_id', $session_id)->get();

        $get_last_id = TransRoomRooms::where('session_id', $session_id)->limit(1)->orderBy('id','DESC')->get();

        if(count($get_last_id) > 0){
            foreach($get_last_id as $key => $val){
                $this->cek_trans = TransRoomRooms::where('person_id', request()->customer_id)->first();
            }
        }

        if($this->cek_available->count() > 0){
            $this->completed = 'next';
            if(isset(TransRoom::where('session_id', $session_id)->first()->id)){
                $this->invoice_id = TransRoom::where('session_id', $session_id)->first()->id;
            }
        }

        foreach($temp_room as $kr => $vr){
            $this->room[$vr->room_id] = $vr->room_id;
        }



        foreach($this->bed as $key => $value){
            foreach($this->cek_available as $k3 => $v3){
                 if($v3->room_id == $value->id){
                     $this->person_r[$v3->person_id] = $value;
                 }
            }
        }

        // if(count($this->customer) > 0){
        //     $this->customer;
        //     $this->person_c = TransRoomPerson::find(request()->customer_id)->person;

        //     foreach($this->bed as $key => $value){
        //         foreach($value as $key2 => $value2){
        //             foreach($this->cek_available as $k3 => $v3){
        //                 if($v3->room_id == $value2['id']){
        //                     $this->person_r[$v3->person_id] = $value2;
        //                 }
        //             }
        //         }
        //     }

        // } else {
        //      return redirect($_SERVER['HTTP_REFERER'])->with(['msg' => 'Silahkan masukkan customer!!']);
        // }

    }

    public function room_chart($room, $persons){
        $session_id = session()->getId();

        DB::beginTransaction();

        try {
            $roomPerson = new TransRoomRooms();

            $roomPerson->session_id = $session_id;
            $roomPerson->person_id = $persons;
            $roomPerson->people = $this->people;
            $roomPerson->room_id = $room;

            $roomPerson->save();

            DB::commit();


           return redirect($_SERVER['HTTP_REFERER'])->with(['msg' => 'Data telah masuk']);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    public function extras_tansaction($bahasa){
        redirect(url($this->langs.'/room_extras/'.$this->invoice_id));
    }

    public function chart_room(){
        $this->bed_show_room = $this->bed;
    }

    public function back_transaction($bahasa){
        redirect(url($this->langs.'/room_order/'));
    }

    public function remove_room_chart($customer_id, $bahasa){
        TransRoomRooms::where('person_id', $customer_id)->delete();

        redirect(url($this->langs.'/room_order/'))->with(['msg-gagal' => 'Data telah dihapus']);
    }


    public function render()
    {
        return view('livewire.custom-transaction.room-transaction')
        ->extends('layouts.front_end.index')
        ->section('content');
    }

}
