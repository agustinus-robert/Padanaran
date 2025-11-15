<?php

namespace App\Livewire\FrontTransaction;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\SignUps;
use App\Models\User;
use DB;

class SignUp extends Component
{

    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $password;
    public $confirm_password;
    public $inc;

    public function check_form(){
        if(!empty($this->first_name)){
            $this->inc++;
        } else if(!empty($this->last_name)){
            $this->inc++;
        } else if(!empty($this->email)){
            $this->inc++;
        } else if(!empty($this->phone)){
            $this->inc++;
        }
    }

    public function password_checker(){
        if(!empty($this->password) && !empty($this->confirm_password)){
            if($this->password == $this->confirm_password){
                $this->inc++;
            } else {
                $this->inc--;
                $this->alert('error', 'Password dan Password Konfirmasi harus sama', [
                    'position' => 'center',
                    'timer' => 3000,
                    'toast' => true,
                ]);
            }
        }
    }

    public function submitSignUp(){
         try {
            DB::beginTransaction();

            $admin = User::create([
                'name' => $this->first_name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'email_verified_at' => date('Y-m-d H:i:s'),
                'role_id' => 2,
                'created_by' => 1,
                'updated_by' => 1
            ]);

            $signUp = new SignUps();
            $signUp->user_id = $admin->id;
            $signUp->first_name = $this->first_name;
            $signUp->last_name = $this->last_name;
            $signUp->email = $this->email;
            $signUp->phone = $this->phone;
            $signUp->save();

            $admin->assignRole('user');
            DB::commit();

            $this->alert('success', 'User Donasi telah berhasil didaftarkan', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => true,
            ]);
        } catch (\PDOException $e) {
            // Woopsy
            dd($e);
            DB::rollBack();
            return false;
        }
    }

    public function render()
    {
        return view('livewire.front-transaction.sign-up')
        ->extends('layouts.front_end.index')
        ->section('content');
    }
}
