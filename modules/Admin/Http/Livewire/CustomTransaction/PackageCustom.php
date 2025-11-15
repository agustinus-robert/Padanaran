<?php

namespace App\Livewire\CustomTransaction;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailablePay;
use App\Models\UserInformation;
use DB;

class PackageCustom extends Component
{


    public $first_name;
    public $last_name;
    public $email;
    public $phone;

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

    public function send_transaction(){

        $inv = random_int(100, 999).strtotime("now");

        $data = [
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'code' => $inv
        ];

        $userInfo = new UserInformation();
        $userInfo->invoice_num = $inv;
        $userInfo->first_name = $this->first_name;
        $userInfo->last_name = $this->last_name;
        $userInfo->email = $this->email;
        $userInfo->phone = $this->phone;
        $userInfo->status = 0;
        $userInfo->account_status = 0;
        $userInfo->save();

        Mail::to($this->email)->send(new MailablePay($data));

        $this->alert('success', 'Nota pembayaran dengan code <b>'.$inv.'</b> Telah dikirimkan pada email anda', [
            'position' => 'center'
        ]);
    }


    public function render()
    {
        return view('livewire.custom-transaction.package-custom')
        ->extends('layouts.front_end.index')
        ->section('content');
    }
}
