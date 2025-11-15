<?php

namespace Modules\Admin\Http\Livewire\Builder;

use Livewire\Attributes\On;
use Livewire\Component;

class FormRefresh extends Component
{
    public $vArr;
    public $vRand;

    public function mount($rand, $arr){
        $this->vArr = $arr;
        $this->vRand = $rand;
    }

    public function render()
    {
        return view('admin::livewire.builder.form-refresh');
    }
}
