<?php

namespace Modules\Account\Http\Livewire\Member;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Auth;
use DB;
use Modules\Account\Models\User;
use Modules\Account\Models\UserRole;
use Modules\Account\Models\MembersDonator;
use Modules\Account\Models\MembersVolunteer;

class RegistrationApp extends Component
{

    public $username_login;
    public $password_login;

    public $fullname_signup;
    public $email_signup;
    public $phone_signup;
    public $username_signup;
    public $password_signup;
    public $address_signup;
    public $note_signup;
    public $role_signup;
    public $cek = '';

    public function mount(Request $request){
       $url = $request->fullUrl();

       $parts = parse_url($url);
       if(isset($parts['query'])){
            parse_str($parts['query'], $query);
            $this->cek = $query['action'];
        }
    }

    public function radios($event){
       if($event == 'signup'){
        $this->cek = 'signup';
       } else {
        $this->cek = 'login';
       }
    }

    public function role_choose($event){
        $this->role_signup = $event;
    }

    public function saveSignup(){
        DB::beginTransaction();

        try {
            $saveUser = User::create([
                'name' => $this->fullname_signup,
                'username' => $this->username_signup,
                'email_address' => $this->email_signup,
                'password' => bcrypt($this->password_signup)
            ]);

            // if($this->role_signup == 1){
            //     $saveMemberDonator = MembersDonator::create([
            //         'user_id' => $saveUser->id,
            //         'typeble_id' => $this->role_signup,
            //     ]);

            //     $memberMetaDonator = MembersDonator::find($saveMemberDonator->id);
            //     $memberMetaDonator->setMeta('fullName', $this->fullname_signup);
            //     $memberMetaDonator->setMeta('phone', $this->phone_signup);
            //     $memberMetaDonator->save();
            // } else {
            //     $saveMemberVolunteer = MembersVolunteer::create([
            //         'user_id' => $saveUser->id,
            //         'typeble_id' => $this->role_signup,
            //     ]);

            //     $memberMetaVolunteer = MembersVolunteer::find($saveMemberVolunteer->id);
            //     $memberMetaVolunteer->setMeta('fullName', $this->fullname_signup);
            //     $memberMetaVolunteer->setMeta('phone', $this->phone_signup);
            //     $memberMetaVolunteer->save();
            // }


            $member = User::find($saveUser->id);
            $member->setMeta('fullName', $this->fullname_signup);
            $member->setMeta('phone', $this->phone_signup);
            $member->setMeta('address', $this->address_signup);
            $member->setMeta('note', $this->note_signup);
            $member->save();

            $role = UserRole::create([
                'user_id' => $saveUser->id,
                'role_id' => 5
            ]);

            $this->alert('success', 'User telah berhasil disimpan', [
                'position' => 'center'
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    public function actionLogin(){
         $data = [
            'username' => $this->username_login,
            'password' => $this->password_login,
        ];

        if(Auth::guard('web')->attempt($data)){
            $url = url('donation/donate/fill_form');

            $u_id = User::where('username', $this->username_login)->get()->first();
            Session::put('user_id', $u_id->id);
            Session::put('name',$this->username_login);
            Session::put('email',$this->password_login);
            Session::put('login',TRUE);

            redirect($url);

        } else {
            dd('login gagal');
        }
    }

    public function render()
    {
        return view('account::livewire.member.registration-app');
    }
}
