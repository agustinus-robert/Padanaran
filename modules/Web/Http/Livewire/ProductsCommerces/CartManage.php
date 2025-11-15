<?php

namespace Modules\Web\Http\Livewire\ProductsCommerces;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use \App\Mail\MailablePay;
use Modules\Pos\Models\Product as ProductData;
use Modules\Pos\Models\Cart as CartData;
use Livewire\Attributes\On;
use DB;
use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;

class CartManage extends Component
{
    use WithPagination;

    public $cartProduct = [];
    public $taxRate = 0.11; // Pajak 10%
    public $cartz;
    public $sessionId = '';

    public function mount()
    {
        $this->sessionId = Session::getId();
        $this->cartz = CartData::where('session_id', $this->sessionId)->whereNull('status')->get();
    }

    // Method untuk menghitung subtotal
    public function calculateSubtotal()
    {
        $subtotal = 0;
        foreach ($this->cartz as $cartItem) {
            $subtotal += $cartItem->qty * $cartItem->product->price;
        }
        return $subtotal;
    }

    // Method untuk menghitung pajak
    public function calculateTax()
    {
        return $this->calculateSubtotal() * $this->taxRate;
    }

    // Method untuk menghitung total (Subtotal + Pajak)
    public function calculateTotal()
    {
        return $this->calculateSubtotal() + $this->calculateTax();
    }

    #[On('refresh')]
    public function refresh()
    {
        $this->mount();  // Or call the appropriate method to refresh the data
    }

    #[On('cartItemUpdate')]
    public function cartItemUpdate($id, $action)
    {
        $item = CartData::where(['session_id' => $this->sessionId, 'product_id' => $id])->whereNull('status')->first(['id', 'qty']);

        if ($item) {
            $newQty = $item->qty + $action;
            if ($newQty < 1) {
                $this->alert('error', 'Produk tidak boleh kurang dari 1', [
                    'position' => 'center',
                ]);
                return;
            }

            $item->update(['qty' => $newQty]);
            $this->dispatch('cart-updated-' . $id, $item->qty);
            // $this->dispatch('countCart', productId: $id);
        }
    }


    public function render()
    {
        $data['carts'] = CartData::where(['session_id' => Session::getId(), 'status' => null])->get();
        $data['subtotal'] = $this->calculateSubtotal();
        $data['tax'] = $this->calculateTax();
        $data['total'] = $this->calculateTotal();
        $posts = ProductData::whereNull('deleted_at')
            ->paginate(10);

        return view('web::livewire.products.index-cart-item', $data);
    }
}
