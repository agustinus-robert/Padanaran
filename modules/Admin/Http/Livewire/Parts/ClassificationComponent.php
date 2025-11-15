<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Classification;

class ClassificationComponent extends Component
{
    public $classifications;
    public $selectedClassification = null;

    public function mount($selectedClassification = null)
    {
        $this->classifications = Classification::all();
        $this->selectedClassification = $selectedClassification;
    }

    public function updatedSelectedClassification($value)
    {
        $this->emit('classificationSelected', $value);
    }

    public function render()
    {
        return view('livewire.classification-component');
    }
}
