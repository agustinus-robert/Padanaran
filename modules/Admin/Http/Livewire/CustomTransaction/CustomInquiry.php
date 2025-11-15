<?php

namespace App\Livewire\CustomTransaction;

use Livewire\Component;

class CustomInquiry extends Component
{
    public $total;
    public $ppn;
    public $grand_total;
    public $people;
    public $dates;


    public function mount($total, $ppn, $grand_total, $people, $date){
        $this->total = $total;
        $this->ppn = $ppn;
        $this->grand_total = $grand_total;
        $this->people = $people;
        $this->dates = $date;
    }

    public function render()
    {
        return view('livewire.custom-transaction.custom-inquiry')->extends('layouts.front_end.trans_package')
        ->section('content');
    }
}
