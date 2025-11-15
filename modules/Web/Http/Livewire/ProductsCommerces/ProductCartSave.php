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

class ProductCartSave extends Component
{


    #[On('product-save-cart')]
    public function addItemToCart($productId)
    {
        $sessionId = session()->getId();
        $sessionData = Session::getId();
        $cartItem = CartData::where('session_id', $sessionId)
            ->where('product_id', $productId)
            ->whereNull('status')
            ->first();

        if ($cartItem) {
            $cartItem->increment('qty');
        } else {
            CartData::create([
                'session_id' => $sessionId,
                'product_id' => $productId,
                'qty' => 1,
            ]);
        }

        $this->alert('success', 'Item Telah tertambahkan', [
            'position' => 'center'
        ]);
        $this->dispatch('countCart', productId: $productId);
    }

    #[On('product-save-cart-redir')]
    public function addItemToCartRedir($productId)
    {
        $sessionId = session()->getId();

        $cartItem = CartData::where('session_id', $sessionId)
            ->where('product_id', $productId)
            ->whereNull('status')
            ->first();

        if ($cartItem) {
            $cartItem->increment('qty');
        } else {
            CartData::create([
                'session_id' => $sessionId,
                'product_id' => $productId,
                'qty' => 1,
            ]);
        }

        $this->alert('success', 'Item Telah tertambahkan', [
            'position' => 'center'
        ]);
        return redirect(route("web::cart.page"));
    }

    public function render()
    {
        return view('web::livewire.products.index-save');
    }
}
