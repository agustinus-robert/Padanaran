<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;

class CategoriesComponent extends Component
{
    public $categories;
    public $selectedCategory = null;

    public function mount($selectedCategory = null)
    {
        $this->categories = Category::all();
        $this->selectedCategory = $selectedCategory;
    }

    public function updatedSelectedCategory($value)
    {
        $this->emit('categorySelected', $value);
    }

    public function render()
    {
        return view('livewire.categories-component');
    }
}
