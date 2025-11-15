<?php

namespace Modules\Admin\Http\Livewire\Modals;
use LivewireUI\Modal\ModalComponent;

class Floor extends ModalComponent
{
    public $showEditModal = false;

    public function edit()
    {
        $this->showEditModal = true;
    }

    public function render()
    {
        return view('admin::livewire.modals.floor');
    }
}