<?php

namespace Modules\Admin\Http\Livewire\Builder;

use Livewire\Attributes\On;
use Livewire\Component;

class ClearTaxoCookie extends Component
{
    #[On('site-taxo')]
    public function index($index){
        
        setcookie('_x_ft_taxo'.$index, FALSE, -1, '/'); 
        setcookie('_x_fy_taxo'.$index, FALSE, -1, '/'); 
        setcookie('_x_v_taxo'.$index, FALSE, -1, '/'); 
    }

    public function render()
    {
        return view('admin::livewire.builder.clear-taxo-cookie');
    }
}
