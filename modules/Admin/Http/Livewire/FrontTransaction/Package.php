<?php

namespace App\Livewire\FrontTransaction;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Package extends Component
{

    public $pack = '';
    public $people = 0;
    public $goto;

    public function customTrans(){
        redirect('id/packages_custom');
    }

    public function check_participant($event){
        $this->people = $event;
        if($event == 1){
            $this->pack = 1;
            $this->alert('info', 'Anda terdaftar sebagai <b>Single Supplement</b>', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => true,
            ]);

        } else if($event >= 2 && $event <= 4){
            $this->pack = 2;
            $this->alert('info', 'Anda terdaftar sebagai <b>2-4 Pax</b>', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => true,
            ]);

        } else if($event >= 5 && $event <= 7){
            $this->pack = 3;
            $this->alert('info', 'Anda terdaftar sebagai <b>5-7 Pax</b>', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => true,
            ]);
        } else if($event >= 8 && $event <= 10 || $event >= 8 && $event >= 10){
            $this->pack = 4;
            $this->alert('info', 'Anda terdaftar sebagai <b>8-10 Pax</b>', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => true,
            ]);
        }
    }

    public function save(){
        if(!empty($this->goto) && !empty($this->pack)){
            $tgl = date('d-m-Y', strtotime($this->goto));
            $this->dispatch('pack-trans', pack: $this->pack, people: $this->people, date: $tgl);
        } else {
              $this->alert('error', 'Harap isi tanggal keberangkatan dan jumlah pack', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => true,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.front-transaction.package')
        ->extends('layouts.front_end.index')
        ->section('content');
    }
}
