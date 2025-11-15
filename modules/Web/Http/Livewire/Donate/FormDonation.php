<?php

namespace Modules\Web\Http\Livewire\Donate;


use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
//use \App\Notifications\GlobalsNotify;
use Modules\Auth\Events\SignedUp;
use \App\Mail\MailablePay;
use Modules\Account\Models\User;
use Modules\Account\Models\UserRole;
use Modules\Account\Models\MembersDonator;
use Modules\Donation\Models\UserInformation;
use Modules\Donation\Models\Donation;
use Modules\Donation\Models\Payment;
use Modules\Donation\Models\PaymentNotification;

use DB;
use Session;

class FormDonation extends Component
{


    public $amount = 0;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $address;
    public $notes;
    public $logins;
    public $inputValue;
    public $pays;
    public $invoices = '';
    public $lang;

    private function signNows($username, $password){
        return [
            'client_id' => env('OAUTH_PASSWORD_CLIENT_ID', ''),
            'client_secret' => env('OAUTH_PASSWORD_CLIENT_SECRET', ''),
            'username' => $username,
            'password' => $password,
            'grant_type' => 'password'
        ];
    }

    public function mount($invoice_id = null, Request $request){
        $this->logins = $request->session()->get('login');
        $this->invoices = $invoice_id;

        // if(!empty($invoice_id)){

        //     $user = UserInformation::where('invoice_num', $invoice_id)->first();
        //     $this->first_name = $user->first_name;
        //     $this->last_name = $user->last_name;
        //     $this->email = $user->email;
        //     $this->phone = $user->phone;
        //     $this->address = $user->address;
        //     $this->notes = $user->note;
        //     $this->pays = round($this->pays, 0);
        //     $this->amount = $user->amount;
        // }

        $this->lang = request()->bahasa;
        $session_id = session()->getId();
    }

    // #[\Livewire\Attributes\On('call-first')]
    // public function donation(){
    //     \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    //     \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
    //     \Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
    //     \Midtrans\Config::$is3ds = env('MIDTRANS_IS_3DS');

    //     $getOrder = '';
    //     //$invoice = PaymentNotification::where('invoice_num', $this->pay_id)->first();

    //     // if(isset($invoice->transaction_status) && $invoice->transaction_status == 'pending'){
    //     //     $getOrder = rand();
    //     // } else {
    //     //     $getOrder = rand();
    //     // }

    //     if($this->amount == 0){
    //         $this->pays = 1000000;
    //     } else if($this->amount == 1){
    //         $this->pays = 5000000;
    //     } else if($this->amount == 2){
    //         $this->pays = 10000000;
    //     } else {
    //         $this->pays = $this->inputValue;
    //     }

    //     $params = array(
    //         'transaction_details' => array(
    //             'order_id' => rand(),
    //             'gross_amount' => $this->pays,
    //         ),
    //         "callbacks" => array(
    //             "finish" => "https://tokoecommerce.com/my_custom_finish/?name=Customer01"
    //         )
    //     );

    //     $snapsToken = \Midtrans\Snap::getSnapToken($params);
    //     $this->dispatch('contentChanged', ['snapToken' => $snapsToken, 'pay_id' => $this->pays]);
    // }

    //#[\Livewire\Attributes\On('payment')]
    public function save(Request $request){
        //$user = User::find($request->session()->get('user_id'));

        // if(!empty($this->invoices)){
        //     $inv = $this->invoices;
        // } else {
            $inv = random_int(100, 999).strtotime("now");
        //}

        // $emailz = '';

        // if(isset($user->id)){
        //     $memberMeta = MembersDonator::where('user_id', $user->id)->get()->first();
        //     $metaM = MembersDonator::find($memberMeta->id);
        //     $emailz = $user->email_address;
        // } else {
        //     $emailz = $this->email;
        // }

        // $data = [
        //     'firstName' => (isset($user->id) ? $user->name : $this->first_name),
        //     'lastName' => (isset($user->id) ? '' : $this->last_name),
        //     'email' => (isset($user->id) ? $user->email : $this->email),
        //     'phone' => (isset($uset->id)  ? $metaM->getMeta('phone') : $this->phone),
        //     'code' => $inv
        // ];

        // Mail::to($emailz)->send(new MailablePay($data));
        if(User::where('email_address', $this->email)->count() == 0 || empty($this->email)){
            DB::beginTransaction();
            try {

                $userInfo = new UserInformation();

                if($this->amount == 0){
                    $this->pays = 1000000;
                } else if($this->amount == 1){
                    $this->pays = 5000000;
                } else if($this->amount == 2){
                    $this->pays = 10000000;
                } else {
                    $this->pays = $this->inputValue;
                }

                $user = User::where('email_address', \Auth()->user()->email_address)->first();
                if(!isset($user->id)){
                    $usernames = $this->first_name.rand(1, 5);
                    $passwords = str()->random(8);

                    $newUser = new User();
                    $newUser->name = $this->first_name.' '.$this->last_name;
                    $newUser->username = $usernames;
                    $newUser->email_address = $this->email;
                    $newUser->password = $passwords;
                    $newUser->save();

                    $data = [
                        'email' =>  $this->email,
                        'first_name' => $this->first_name,
                        'username' => $usernames,
                        'password' => $passwords
                    ];

                    Mail::to($this->email)->send(new MailablePay($data));

                    if($newUser->save()){
                        $userRole = new UserRole();
                        $userRole->user_id = $newUser->id;
                        $userRole->role_id = 2;
                        if($userRole->save()){
                            event(new SignedUp($newUser));

                            // $response = Http::withOptions(['verify' => false])->post(route('passport.token'), $request->transformed());
                            // event(new SignedIn($response->object(), $request->has('remember')));
                        }
                    }
                }

                $userInfo->invoice_num = $inv;
                $userInfo->email = (isset($user->id) ? $user->email_address : $this->email);
                $userInfo->note = $this->notes;
                $userInfo->status = 0;
                $userInfo->amount = $this->amount;


                $userInfo->save();

                if($userInfo->save()){

                    // if($status == 'success'){
                    //         $userInfos = UserInformation::where('invoice_num', $inv)->first();

                    //         $updateUserInfo = UserInformation::find($userInfos->id);
                    //         $updateUserInfo->status = 1;
                    //         $updateUserInfo->save();
                    //     }

                        $getifPayment = Payment::where('invoice_num', $inv)->first();
                        $getifNotif = PaymentNotification::where('invoice_num', $inv)->first();

                        if(isset($getifPayment->invoice_num)){
                            $getPayment = Payment::find($getifPayment->id);

                            $dataUpdatePayment = [
                                'pay' => $this->pays
                            ];

                            $getPayment->update($dataUpdatePayment);
                        } else {
                            $sendPayment = new Payment();
                            $sendPayment->invoice_num =$inv;
                            $sendPayment->pay = $this->pays;
                            $sendPayment->save();
                        }

                        // if(isset($getifNotif->invoice_num)){
                        //     $getNotif = PaymentNotification::find($getifNotif->id);
                        //     $dataUpdateNotif = [
                        //         'invoice_num' => $inv,
                        //         'payment_type' => $payment_type,
                        //         'transaction_status' => $transaction_status,
                        //         'transaction_id' => $transaction_id,
                        //         'notify_detailed_info' => json_encode($detail)
                        //     ];

                        //     $getNotif->update($dataUpdateNotif);
                        // } else {
                        //     $notifPayment = new PaymentNotification();
                        //     $notifPayment->invoice_num = $inv;
                        //     $notifPayment->payment_type = $payment_type;
                        //     $notifPayment->transaction_status = $transaction_status;
                        //     $notifPayment->transaction_id = $transaction_id;
                        //     $notifPayment->notify_detailed_info = json_encode($detail);
                        //     $notifPayment->save();
                        // }
                }



                DB::commit();
                // $request->session()->put('user-email', $userInfo->email);

                $callbacks = route('portal::guest.donation.show', ['donation' => $inv]);
                //dd($callbacks);
                return redirect($callbacks);
            }catch(\Exception $e) {
                 DB::rollback();
                 dd($e);
            }
        } else {
            $this->alert('error', 'Email sudah pernah didaftarkan',  [
               'position' => 'center'
            ]);
        }
    }


    public function render()
    {
        return view('web::livewire.donation.section-donation');
    }
}
