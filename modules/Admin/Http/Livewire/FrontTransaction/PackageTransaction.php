<?php

namespace App\Livewire\FrontTransaction;

use Livewire\Component;
use Livewire\Attributes\On;

class PackageTransaction extends Component
{

    #[On('pack-trans')] 
    public function transaction($pack, $people, $date){
      return redirect('package_inquiry/'.$pack.'/'.$people.'/'.$date);
    }

    // public function render()
    // {
    //     return view('livewire.front-transaction.package-transaction');
    // }
}
