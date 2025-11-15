<?php

namespace Modules\Poz\Http\Livewire\Transaction;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Modules\Poz\Models\Category;
use Modules\Poz\Models\Brand;



class PosModals extends Component
{

    public $checkboxShortcut = [];

    protected $listeners = ['shortcutSaved' => 'onShortcutSaved'];

    public $inv = [];
    public $selectedItems = [];

    public function mount()
    {
        $this->inv['outlet'] = auth()->user()->current_outlet_id;
        $this->inv['discount'] = 0;
        $this->inv['ppn'] = '11%';

        $this->selectedItems = [];
    }

    public function saveShortcut()
    {
        if (count($this->checkboxShortcut) > 4) {
             LivewireAlert::error('Shortcut tidak boleh lebih dari 4', [
                'position' => 'center'
            ]);
            return;
        }

        \Modules\Poz\Models\Brand::query()->update(['is_shortcut' => 0]);

        foreach ($this->checkboxShortcut as $value) {
            $brand = \Modules\Poz\Models\Brand::find($value);
            if ($brand) {
                $brand->is_shortcut = 1;
                $brand->save();
            }
        }

        $this->dispatchBrowserEvent('shortcutSaved');
        $this->emit('shortcutSaved');
    }

    public function onShortcutSaved()
    {
        $this->alert('success', 'Shortcut berhasil disimpan', ['position' => 'center']);
    }

    public function render()
    {
        $outletId = $this->inv['outlet'];

        $brands = Brand::whereNull('deleted_at')->whereHas('outlets', fn($q) => $q->where('outlet_id', $outletId))->get();
        $categories = Category::whereNull('deleted_at')->whereHas('outlets', fn($q) => $q->where('outlet_id', $outletId))->get();


        return view('poz::livewire.transaction.posModals', [
            'brand' => $brands,
            'category' => $categories,
        ]);
    }
}
