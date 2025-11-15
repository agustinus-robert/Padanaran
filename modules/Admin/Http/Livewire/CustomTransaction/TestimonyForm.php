<?php

namespace App\Livewire\CustomTransaction;

use Livewire\Component;
use App\Models\Testimonial;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class TestimonyForm extends Component
{
    use WithFileUploads;

    public $full_name;
    public $profesion;
    public $photo;
    public $testimony;
    public $feedback;

    public function save(){
        $name = md5($this->photo . microtime()).'.'.$this->photo->extension();
        $location = 'image_testimony/'.uniqid();


        $this->photo->storeAs($location, 'photos', 'public');

        Testimonial::create([
            'name' => $this->full_name,
            'image' => $this->photo,
            'testimoni' => $this->testimony,
            'feedback' => $this->feedback
        ]);

        $this->alert('success', 'Testimoni dan Rating telah terkirim', [
             'position' => 'center'
        ]);
        $this->reset();
    }

    public function render()
    {
        return view('livewire.custom-transaction.testimony-form');
    }
}
