<?php

namespace App\Livewire\CustomTransaction;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Http\Request;
use App\Models\extraItems;
use App\Models\TransRoom;
use App\Models\TransRoomPerson;
use App\Models\TransRoomRooms;
use App\Models\TransExtras;
use Illuminate\Support\Facades\Http;

use DB;

class ExtraTransaction extends Component
{

    public $extra = [];

    public $customer = [];
    public $room = [];
    public $cek_available = [];
    public $person_c = '';
    public $person_r = [];
    public $cek_trans = '';

    public $completed = '';
    public $invoice_id = '';
    public $sessExtra = [];

    public function mount(){
        $session_id = session()->getId();


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

        $transExtra = TransExtras::where('session_id', $session_id)->get();
        if($transExtra->count() > 0){
            foreach($transExtra as $key => $value){
                $this->sessExtra[$value->id_extras] = $value->id_extras;
            }

            $this->invoice_id = TransRoom::where('session_id', $session_id)->first()->id;

            $this->completed = 'next';
        }
    }

    public function extra_chart($extras){
        $session_id = session()->getId();


        DB::beginTransaction();

        try {
            $roomExtra = new TransExtras();

            $roomExtra->session_id = $session_id;
            $roomExtra->id_extras = $extras;

            $roomExtra->save();

            DB::commit();


           return redirect($_SERVER['HTTP_REFERER'])->with(['msg' => 'Data telah masuk']);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    public function invoice_transaction($bahasa){
        redirect(url($bahasa.'/room_invoice/'.$this->invoice_id));
    }

    public function remove_extra_chart($id){
        $session_id = session()->getId();

        TransExtras::where(['id_extras' => $id, 'session_id' => $session_id])->delete();

        return redirect($_SERVER['HTTP_REFERER'])->with(['msg-gagal' => 'Data telah dihapus']);
    }


    public function render()
    {
        return view('livewire.custom-transaction.extra-transaction')
        ->extends('layouts.front_end.index')
        ->section('content');
    }
}
