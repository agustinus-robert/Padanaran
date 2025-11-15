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
use Illuminate\Support\Facades\Cache;
use DB;
use Illuminate\Support\Facades\Session;

class ProductCartTemporary extends Component
{
    //    protected $listeners = ['countCart'];

    public $countNum = 0;
    public $products = [];

    public function mount()
    {
        $sessionId = Session::getId();
        $cartCountItem = CartData::where(['session_id' => $sessionId])
            ->whereNull('status');
        $this->countNum = $cartCountItem->sum('qty');
        $this->products = $cartCountItem->get();
    }

    #[On('countCart')]
    public function countCart($productId)
    {
        $sessionId = session()->getId();
        $this->countNum = CartData::where(['session_id' => $sessionId])->whereNull('status')->sum('qty');

        $this->reset(); // Reset semua data dalam komponen
        $this->mount(); // Panggil ulang mount() untuk fetch data baru
        // Opsional: Berikan alert untuk pengguna
    }


    public function render()
    {
        return view('web::livewire.products.index-cart');
    }
}
