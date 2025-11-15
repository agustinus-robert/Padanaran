<?php

namespace App\Livewire\CustomTransaction;

use Livewire\Component;
use App\Models\FacilityTransaction;
use App\Models\TransExtras;
use Illuminate\Support\Facades\Http;

class FacilityInvoicePrint extends Component
{
    public $extra = [];
    public $bed = [];
    public $bed_facility = [];
    public $person = [];

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
        $this->trans_id = request()->invoice_id;
        $this->langs = request()->bahasa;
        $this->person = FacilityTransaction::where('id', $this->trans_id)->get()->first();


        $response_facility_type = Http::withHeaders(
        [
            "X-API-KEY" => "nyyEjH2EA6haC2VJ3Dp9QmsJ5GdnyKN52oj+kNxaXW0=",
            "Accept" => "application/json",
        ])->get("https://emp.digipemad.com/api/booking/restaurant-tables");

        if($response_facility_type->getStatusCode() == 200){
            $this->extra = json_decode($response_facility_type->getBody()->getContents(), true)['data'];
        } else {
            $this->extra = extraItems::get();
        }

        $transFacility = TransExtras::where('session_id', $this->trans_id)->get();

        foreach($transFacility as $key => $val){
            $this->transExtra[$val->id_extras] = $val;
        }

    }

    public function render()
    {
        return view('livewire.custom-transaction.facility-invoice-print')
        ->extends('layouts.front_end.trans_package')
        ->section('content');
    }
}
