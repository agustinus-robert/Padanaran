<?php

namespace App\Livewire\CustomTransaction;

use Livewire\Component;
use App\Models\Payment;
use App\Models\UserInformation;
use App\Models\PaymentNotification;
use Livewire\Attributes\On;
use DB;

class StatusPaymentTransaction extends Component
{
    public $status = '';
    public $user = '';
    public $payment = '';
    public $detail_info = '';
    public $invoice = '';

    public function mount($pay_id){
        $notif = PaymentNotification::where('invoice_num', $pay_id)->first();
        $userInfo = UserInformation::where('invoice_num', $pay_id)->first();
        $payments = Payment::where('invoice_num', $pay_id)->first();

        $this->status = $notif->transaction_status;
        $this->payment = $payments->pay;
        $this->detail_info = json_decode($notif->notify_detailed_info);
        $this->user = $userInfo;
        $this->invoice = $pay_id;
    }

    public function render()
    {
        return view('livewire.custom-transaction.status-payment-transaction')
        ->extends('layouts.front_end.index')
        ->section('content');
    }
}
