<?php

namespace App\Livewire\CustomTransaction;

use Livewire\Component;
use Livewire\Attributes\On;

class CsTransaction extends Component
{
    #[On('custom-trans')] 
    public function transaction($total, $ppn, $grand_total, $people, $date){
      return redirect('custom_inquiry/'.$total.'/'.$ppn.'/'.$grand_total.'/'.$people.'/'.$date);
    }
}
