<?php

namespace Modules\Poz\Http\Livewire\Transaction;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use Modules\poz\Models\Product;
use Modules\poz\Models\Category;
use Modules\poz\Models\Brand;
use Modules\poz\Models\Warehouse;
use Modules\poz\Models\PurchaseItems;
use Livewire\Attributes\On;
use DB;

class Information extends Component
{
    use WithFileUploads;

    public $form = [];
    public $products = '';
    public $categories = '';
    public $brand = '';

    public function mount($id)
    {
        $this->products = Product::find($id);
        $this->categories = Category::find($this->products->category_id);
        $this->brand = Brand::find($this->products->brand_id);
    }

    public function getTotalQuantityByWarehouse($productId)
    {
        // Ambil data purchase_items yang memiliki product_id dan warehouse_id tertentu
        $purchaseItems = PurchaseItems::where('product_id', $productId)
            ->with('warehouse') // Mengambil data warehouse terkait
            ->get();

        // Inisialisasi array untuk menyimpan total qty berdasarkan warehouse
        $warehouseTotals = [];

        foreach ($purchaseItems as $item) {
            $warehouseName = $item->warehouse->name;  // Mengambil nama gudang dari relasi warehouse
            if (!isset($warehouseTotals[$warehouseName])) {
                $warehouseTotals[$warehouseName] = 0;
            }
            $warehouseTotals[$warehouseName] += $item->qty;  // Menambahkan qty ke total gudang terkait
        }

        return $warehouseTotals; // Mengembalikan total qty per warehouse
    }

    public function modalClosed()
    {
        $this->reset(); // Reset semua data jika perlu
    }

    public function render()
    {
        $data['productInfo'] = $this->products;
        $data['categoriesInfo'] = $this->categories;
        $data['brandInfo'] = $this->brand;
        $data['warehouseTotalQty'] = $this->getTotalQuantityByWarehouse($this->products->id);

        return view('poz::livewire.transaction.information', $data);
    }
}
