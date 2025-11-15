<?php

namespace Modules\Web\Http\Livewire\Donate;

use Livewire\Component;
use Modules\Donation\Models\Payment;
use Modules\Donation\Models\UserInformation;
use Modules\Donation\Models\PaymentNotification;
use Livewire\Attributes\On;
use DB;

class NotesPaymentTransaction extends Component
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
        return view('donation::livewire.donate.notes_payment_transaction');
    }
}
