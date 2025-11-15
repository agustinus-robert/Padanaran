<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Brand;

class BrandsComponent extends Component
{
    public $brands;
    public $selectedBrand = null;

    public function mount($selectedBrand = null)
    {
        $this->brands = Brand::all();
        $this->selectedBrand = $selectedBrand;
    }

    public function updatedSelectedBrand($value)
    {
        $this->emit('brandSelected', $value);
    }

    public function render()
    {
        return view('livewire.brands-component');
    }
}
