<?php

namespace App\Livewire\CustomTransaction;

use App\Models\TransRoom;
use App\Models\TransRoomPerson;
use App\Models\TransRoomRooms;
use App\Models\TransExtras;
use App\Models\extraItems;
use App\Models\Bed;
use Illuminate\Support\Facades\Http;

use DB;
use Livewire\Component;

class InvoicePrint extends Component
{
    public $extra = [];
    public $bed = [];
    public $inv_print = [];
    public $person = [];

    public $transExtra;
    public $transPerson;
    public $transRoom;
    public $transExtras;
    

    public function mount(){
        $session_id = session()->getId();
        $tr_room = TransRoom::where('session_id', $session_id)->get()->first();
        
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
        } else {
            $this->bed = Bed::all();    
        }

        $this->person = TransRoomPerson::where('session_id', $session_id)->get()->first();
        $this->transExtra = TransRoom::where('session_id', $session_id)->get()->first();
        $transRom = TransRoomRooms::where('session_id', $session_id)->get();
        $transExtrs = TransExtras::where('session_id', $session_id)->get();

        foreach($transRom as $key => $val){
            $this->transPerson[$val->id] = $val;
        }

        foreach($transRom as $key => $val){
            $this->transRoom[$val->room_id] = $val;
        }

        foreach($transExtrs as $key => $val){
            $this->transExtras[$val->id_extras] = $val;
        }
    }

    public function render()
    {
        return view('livewire.custom-transaction.invoice-print')
        ->extends('layouts.front_end.trans_package')
        ->section('content');
    }
}
