<?php

namespace App\Livewire\FrontTransaction;

use Livewire\Component;
use App\Models\TransRoom;
use App\Models\TransRoomPerson;
use App\Models\TransRoomRooms;
use App\Models\TransExtras;
use DB;

class FrontsTrans extends Component
{
    public $in_date;
    public $out_date;
    public $people = 1;

    public $trans_room = [];
    public $lang;

     public function mount(){
        $this->lang = request()->bahasa;
        $session_id = session()->getId();
        $transa = DB::table('transaction_room')->where('session_id', $session_id)->first();

        if(is_object($transa) && isset($transa->type) && $transa->type == 2){
            $this->in_date = $transa->date_in;
            $this->out_date = $transa->date_out;
            // $this->cupon = $transa->cupon;

            // $persons = DB::table('transaction_room_has_person')->where('session_id', $session_id)->get();

            // $this->room = count($persons);

            // if(count($persons) > 0){
            //     foreach($persons as $key => $value){
            //         $this->trans_room['person'][$key] = $value->person;
            //         $this->trans_room['note'][$key] = $value->notes;
            //     }
            // }
        }
    }

    public function get_people($persons){
        $this->people = $persons;
    }

     public function check_room($lang){
        $session_id = session()->getId();
        $type = 2;
        $counts = 1;

        $update = DB::table('transaction_room')->where('session_id', session()->getId())->get();

        DB::beginTransaction();

        try {
            if($update->count() > 0){

                $TransRoomPersonDelete = TransRoomPerson::where('session_id', $session_id)->delete();
                $TransRoomMrooms = TransRoomRooms::where('session_id', $session_id)->delete();
                $TransExtra = TransExtras::where('session_id', $session_id)->delete();
         
            } 

            $transRooms = TransRoom::firstOrNew(array('session_id' => $session_id));
            $transRooms->session_id = session()->getId();
            $transRooms->type = 2;
            $transRooms->date_in = date('Y-m-d', strtotime($this->in_date));
            $transRooms->date_out = date('Y-m-d', strtotime($this->out_date));
            $transRooms->save();
        
            

            DB::commit();
            
        
            redirect(url($this->lang.'/room_order/'));
                        
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    public function render()
    {
        return view('livewire.front-transaction.fronts-trans');
    }
}
