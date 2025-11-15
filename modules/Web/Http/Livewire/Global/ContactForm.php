<?php

namespace Modules\Web\Http\Livewire\Global;


use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use \App\Mail\MailablePay;
use Modules\Admin\Models\Contact;
use Modules\Admin\Models\ContactOrganization;

use DB;
use Session;

class ContactForm extends Component
{

    public $first_name;
    public $last_name;
    public $org;
    public $message;
    public $subject;
    public $email;
    public $tab = 'individual';

    public function resets(){
        $this->first_name = '';
        $this->last_name = '';
        $this->org = '';
        $this->message = '';
        $this->subject = '';
        $this->email = '';
    }

    public function tabAction($action){
        $this->tab = $action;
        $this->resets();
    }


    public function save(Request $request){

        DB::beginTransaction();
        try {

            if($this->tab == 'individual'){
                $saving = new Contact();
                $saving->first_name = $this->first_name;
                $saving->last_name = $this->last_name;
            } else {
                $saving = new ContactOrganization();
                $saving->name = $this->org;
            }

            $saving->email = $this->email;
            $saving->subject = $this->subject;
            $saving->message = $this->message;
            $saving->save();

            $this->alert('success', 'Kontak Berhasil Disimpan',  [
               'position' => 'center'
            ]);

            DB::commit();
            $this->resets();
        }catch(\Exception $e) {
             DB::rollback();
             dd($e);
        }
    }

    public function render()
    {
        return view('web::livewire.contact.contact-form');
    }
}
