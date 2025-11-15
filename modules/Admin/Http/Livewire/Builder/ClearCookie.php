<?php

namespace Modules\Admin\Http\Livewire\Builder;

use Livewire\Attributes\On;
use Livewire\Component;


class ClearCookie extends Component
{

    #[On('site-cookie')]
    public function index($index){
        
        setcookie('_x_ft'.$index, FALSE, -1, '/'); 
        setcookie('_x_fy'.$index, FALSE, -1, '/'); 
        setcookie('_x_v'.$index, FALSE, -1, '/');  
    }

    public function render()
    {
        return view('admin::livewire.builder.clear-cookie');
    }
}
