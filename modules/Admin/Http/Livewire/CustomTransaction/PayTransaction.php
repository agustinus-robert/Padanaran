<?php

namespace App\Livewire\CustomTransaction;

use Livewire\Component;
use App\Models\Payment;
use App\Models\UserInformation;
use App\Models\PaymentNotification;
use Livewire\Attributes\On;
use DB;

class PayTransaction extends Component
{
    public $pay_id;
    public $snapToken;
    public $donate;
    public $moneys;

    public function mount($pay_id){
        $get_payment = PaymentNotification::where('invoice_num', $pay_id)->first();

        if(isset($get_payment->transaction_status) && $get_payment->transaction_status == 'settlement'){
            return redirect('invoice/'.$get_payment->invoice_num)->with('msg', "Pembayaran pada transaksi ini sudah dilakukan"); 
        } else {
            if(isset($get_payment->transaction_status) && $get_payment->transaction_status == 'pending'){
                $this->moneys = (int) json_decode($get_payment->notify_detailed_info)->gross_amount;
            }

            $this->pay_id = $pay_id;
        }
    }

    #[\Livewire\Attributes\On('call-first')]
    public function donation($pay){
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        \Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        \Midtrans\Config::$is3ds = env('MIDTRANS_IS_3DS');

        $getOrder = '';
        $invoice = PaymentNotification::where('invoice_num', $this->pay_id)->first();

        // if(isset($invoice->transaction_status) && $invoice->transaction_status == 'pending'){
        //     $getOrder = rand();
        // } else {
        //     $getOrder = rand();
        // }

        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => $pay,
            ),
            "callbacks" => array(
                "finish" => "https://tokoecommerce.com/my_custom_finish/?name=Customer01"
            )
        );

        $this->donate = $pay;

        $snapsToken = \Midtrans\Snap::getSnapToken($params);
        $this->dispatch('contentChanged', ['snapToken' => $snapsToken, 'pay_id' => $this->pay_id]);
    }

    #[\Livewire\Attributes\On('payment')]
    public function successPayDonation($status, $paymentid, $amount, $order_id, $payment_type, $transaction_status, $transaction_id, $detail){
        DB::beginTransaction();
        try {
                if($status == 'success'){
                    $userInfos = UserInformation::where('invoice_num', $paymentid)->first();

                    $updateUserInfo = UserInformation::find($userInfos->id);
                    $updateUserInfo->status = 1;
                    $updateUserInfo->save();
                }

                $getifPayment = Payment::where('invoice_num', $paymentid)->first();
                $getifNotif = PaymentNotification::where('invoice_num', $paymentid)->first();

                if(isset($getifPayment->invoice_num)){
                    $getPayment = Payment::find($getifPayment->id);
                    
                    $dataUpdatePayment = [
                        'pay' => $this->moneys
                    ];

                    $getPayment->update($dataUpdatePayment);
                } else {
                    $sendPayment = new Payment();
                    $sendPayment->invoice_num =$paymentid;
                    $sendPayment->pay = $amount;
                    $sendPayment->save();
                }

                if(isset($getifNotif->invoice_num)){
                    $getNotif = PaymentNotification::find($getifNotif->id);
                    $dataUpdateNotif = [
                        'invoice_num' => $paymentid,
                        'payment_type' => $payment_type,
                        'transaction_status' => $transaction_status,
                        'transaction_id' => $transaction_id,
                        'notify_detailed_info' => json_encode($detail)
                    ];

                    $getNotif->update($dataUpdateNotif);
                } else {
                    $notifPayment = new PaymentNotification();
                    $notifPayment->invoice_num = $paymentid;
                    $notifPayment->payment_type = $payment_type;
                    $notifPayment->transaction_status = $transaction_status;
                    $notifPayment->transaction_id = $transaction_id;
                    $notifPayment->notify_detailed_info = json_encode($detail);
                    $notifPayment->save();
                }
                
                 DB::commit();

                return redirect('invoice/'.$this->pay_id); 

        }catch(\Exception $e) {
             DB::rollback();
             dd($e);
        }
    }


    public function render()
    {
        return view('livewire.custom-transaction.pay-transaction')
        ->extends('layouts.front_end.index')
        ->section('content');
    }
}
