<?php

namespace App\Livewire\FrontTransaction;

use Livewire\Component;

class PackageInquiry extends Component
{
    public $packed;
    public $people;
    public $dates;
    public $money = 0;
    public $tipe = '';
    public function mount($pack, $people, $date){
        $this->dates = $date;
        if($this->packed == 1){
            $this->packed = 1;
            $this->money = 3195000;
            $this->tipe = 'Single Supplement';
            $this->people = $people;
        } else if($pack == 2){
            $this->packed = 2;
            $this->money = 4395000;
            $this->tipe = '2-4 Pax';
            $this->people = $people;
        } else if($pack == 3){
            $this->packed = 3;
            $this->money = 3520000;
            $this->tipe = '5-7 Pax';
            $this->people = $people;
        } else if($pack == 4){
            $this->packed = 4;
            $this->money = 3195000;
            $this->tipe = '8-10 Pax';
            $this->people = $people;
        }
    }

    public function render()
    {
        return view('livewire.front-transaction.package-inquiry')->extends('layouts.front_end.trans_package')
        ->section('content');
    }
}
