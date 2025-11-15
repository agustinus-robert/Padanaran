<?php

namespace Modules\Web\Http\Livewire\Global;


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

class FormVolunteer extends Component
{

    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $address;
    public $notes;
    public $logins;
    public $inputValue;
    public $lang;

    public function mount(Request $request){
        $this->lang = request()->bahasa;
    }

    public function save(Request $request){
        //$user = User::find($request->session()->get('user_id'));

        // if(!empty($this->invoices)){
        //     $inv = $this->invoices;
        // } else {
        //    $inv = random_int(100, 999).strtotime("now");
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
        if(\Auth::user()->role->role_id == 2){
            $callbacks = route('portal::guest.dashboard.index');
            return redirect($callbacks);
        } else if(User::where('email_address', $this->email)->count() == 0){
            DB::beginTransaction();
            try {

                $user = User::where('email_address', Session::get('user-email'))->first();
                if(!isset($user->id)){
                    $newUser = new User();
                    $newUser->name = $this->first_name.' '.$this->last_name;
                    $newUser->username = $this->first_name;
                    $newUser->email_address = $this->email;
                    $newUser->password = 'admin';
                    $newUser->save();

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

                DB::commit();
                $callbacks = route('portal::guest.dashboard.index');
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
        return view('web::livewire.volunteer.section-volunteer');
    }
}
