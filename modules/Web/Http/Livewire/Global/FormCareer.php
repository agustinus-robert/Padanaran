<?php

namespace Modules\Web\Http\Livewire\Global;


use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use Modules\Admin\Models\CareerData;

use DB;
use Session;

class FormCareer extends Component
{
	use WithFileUploads;

    public $fname;
    public $fphone;
    public $femail;
    public $faddress;
    public $fFiles;
    public $careerId;


    public function mount($careerId, Request $request){
        $this->lang = request()->bahasa;
        $this->careerId = $careerId;
    }

    private function clean(){
    	$this->fname = '';
    	$this->fphone = '';
    	$this->femail = '';
    	$this->faddress = '';
    	$this->fFiles = '';
    	$this->careerId = '';
    }

    public function submitForm(){
    	$data = [
    		'post_id' => $this->careerId,
    		'name' => $this->fname,
    		'phone' => $this->fphone,
    		'email' => $this->femail,
    		'address' => $this->faddress
    	];


    	$location = 'files_posting/'.$this->careerId.'/'.uniqid();

    	if(!empty($this->dropify)){
            $data['file'] = $location.'/'.$this->fFiles->getFilename();
        }


        if(!empty($this->fFiles)){
            $this->fFiles->storeAs($location, $this->fFiles->getFilename(), 'public');
        }

        if(CareerData::create($data) == true){
        	$this->alert('success', 'Data anda telah terkirim', [
			    'position' => 'center'
			]);
        }

        $this->clean();
    }

    public function render()
    {
        return view('web::livewire.career.career-form');
    }
}
