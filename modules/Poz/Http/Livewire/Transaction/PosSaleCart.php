<?php

namespace Modules\Poz\Http\Livewire\Transaction;

use Livewire\Component;

class PosSaleCart extends Component
{
    public $selectedItems = [];
    public $grandTotal = 0;
    public $subTotal = 0;
    public $inv = [];

    public function mount($selectedItems, $inv)
    {
        $this->selectedItems = $selectedItems;
        $this->inv = $inv;
    }

    public function increaseQty($index)
    {
        $this->emit('increaseQty', $index);
    }

    public function decreaseQty($index)
    {
        $this->emit('decreaseQty', $index);
    }

    public function removeItem($itemId)
    {
        $this->emit('removeItem', $itemId);
    }

    public function updateQty($index, $qty)
    {
        $this->emit('updateQty', $index, $qty);
    }

    public function clearItem()
    {
        $this->emit('clearItem');
    }

    public function updateDiscount()
    {
        $this->emit('updateDiscount');
    }

    public function eraseProduct($productId)
    {
        $this->emit('eraseProduct', $productId);
    }

    public function render()
    {
        return view('poz::livewire.transaction.posSaleCart');
    }
}
