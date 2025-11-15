<?php

namespace Modules\Poz\Http\Livewire\Transaction;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use Modules\Poz\Models\Product;
use Modules\Poz\Models\Warehouse;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Modules\Poz\Repositories\SaleRepository;
use Modules\Poz\Models\Sale as SaleData;
use Modules\Poz\Models\PurchaseItems;
use Modules\Poz\Models\SaleItems;
use Modules\Poz\Models\ProductStock;
use Modules\Poz\Models\Purchase;
use Modules\Poz\Models\SaleDirectCart;
use Modules\Poz\Models\Adjustment;
use Modules\Poz\Models\SaleDirect;
use Modules\Account\Models\UserToken;
use Illuminate\Support\Carbon;
use DB;

class Sale extends Component
{
    use WithFileUploads, SaleRepository;

    public $form = [];
    public $salesRef = [];
    public $inv = [];
    public $action;
    public $categoryHasSub = '';
    public $subCategory = [];
    public $query = ''; // Query pencarian
    public $results = []; // Hasil pencarian
    public $selectedItems = []; // Data yang dipilih oleh pengguna
    public $grandTotal = 0;
    public $subtotal = 0;

    public function mount($action, Request $req)
    {
        $id = $req->sale;
        $this->action = $action;
        $this->inv['ppn'] = '11%';

        if (!empty($id) && is_string($id)) {
            $this->form['id'] = $id;

            $this->action = 'Perbarui';
            $sale = SaleData::find($id);

            $this->form['code'] = $sale['reference'];
            $this->inv['sale_date'] = date('Y-m-d H:i:s', strtotime($sale['sale_date']));
            $this->inv['outlet_id'] = SaleItems::where('sale_id', $id)->first()->outlet_id;

            $this->selectedItems = $sale->saleItems->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'name' => $item->product->name,
                    'qty' => $item->qty,
                    'price' => $item->product->price
                ];
            })->toArray();

            $this->grandTotal = $sale->grand_total;
            $this->inv['discount'] = $sale->discount;

            $this->inv['sale_status'] = $sale->sale_status;
        } else {

            $this->action = 'Tambah';
            $digits = '0123456789';
            $randomNumbers = substr(str_shuffle(str_repeat($digits, 10)), 0, 10);
            $this->form['code'] = $randomNumbers;
        }

        $this->form['outlet'] = request()->query('outlet', auth()->user()->current_outlet_id);
    }

    private function resulterProduct($outletId, $selectedItems = null)
    {
        $selectedItems = $selectedItems ?? $this->selectedItems;

        $today = now()->toDateString(); // gunakan Carbon jika perlu kustomisasi waktu

        $this->results = Product::withSum([
            'productStockAdjustItems as stock_plus_qty' => function ($query) use ($today) {
                $query->withoutGlobalScopes()
                    ->where('status', 'plus')
                    ->whereDate('created_at', $today); // ⛔️ DIKUNCI HARI INI
            },
            'productStockAdjustItems as stock_minus_qty' => function ($query) use ($today) {
                $query->withoutGlobalScopes()
                    ->where('status', 'minus')
                    ->whereDate('created_at', $today); // ⛔️ DIKUNCI HARI INI
            }
        ], 'qty')
            ->where('name', 'ilike', '%' . $this->query . '%')
            ->whereHas('productStockAdjustItems', function ($query) use ($today) {
                $query->where('status', 'plus')
                    ->where('qty', '>', 0)
                    ->whereDate('created_at', $today); // ⛔️ DIKUNCI HARI INI
            })
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            })
            ->limit(5)
            ->get();

        // Hitung stock tersisa & kurangi dengan qty yang dipilih
        $this->results = $this->results->map(function ($product) use ($selectedItems) {
            $plus = (float) ($product->stock_plus_qty ?? 0);
            $minus = (float) ($product->stock_minus_qty ?? 0);
            $available = $plus - $minus;

            foreach ($selectedItems as $item) {
                if ($item['id'] == $product->id) {
                    $available -= $item['qty'];
                }
            }

            $product->available_stock = max($available, 0);
            return $product;
        });
    }




    public function updatedQuery()
    {
        // if(empty($this->inv['warehouse_id'])){
        //     $this->results = [];
        //     $this->alert('error', 'Gudang harus dipilih', [
        //         'position' => 'center'
        //     ]);

        //     $this->query = '';
        //     return;
        // }

        // if (strlen($this->query) < 3) {
        //     $this->results = [];
        //     return;
        // }

        $outletId = $this->form['outlet'];

        // $this->results = Product::withSum('purchaseItems', 'qty')->where('name', 'ilike', '%' . $this->query . '%')
        //     ->whereHas('purchaseItems', function ($query) {
        //         $query->where('qty', '>', 0);
        //     })
        //     ->whereHas('outlets', function ($query) use ($outletId) {
        //         $query->where('outlet_id', $outletId);
        //     })
        //     ->limit(5)
        //     ->get();
        // $this->results = Product::withSum(['productStockAdjustItems as stock_plus_qty' => function ($query) {
        // $query->withoutGlobalScopes()
        //       ->where('status', 'plus');
        // }], 'qty')
        // ->where('name', 'ilike', '%' . $this->query . '%')
        // ->whereHas('productStockAdjustItems', function ($query) {
        //     $query->where('status', 'plus')
        //         ->where('qty', '>', 0);
        // })
        // ->whereHas('outlets', function ($query) use ($outletId) {
        //     $query->where('outlet_id', $outletId);
        // })
        // ->limit(5)
        // ->get();

        $this->resulterProduct($outletId, []);



        //->toSql();

    }

    // Fungsi untuk menambahkan item ke dalam tabel
    public function addItem($itemId, Request $request)
    {
        $getTokenUser = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $getTokenUser)->first();

        $item = collect($this->results->toArray())->firstWhere('id', $itemId);

        if (!$item) {
            return false;
        }

        $existingItemIndex = collect($this->selectedItems)->search(function ($selected) use ($itemId) {
            return $selected['id'] === $itemId;
        });

        $today = Carbon::today();

        // STOK MASUK
        $stockIn = ProductStock::where('product_id', $itemId)
            ->whereIn('stockable_type', [
                Purchase::class,
                Adjustment::class,
            ])
            ->where('status', 'plus')
            ->whereDate('created_at', $today)
            ->sum('qty');

        // STOK KELUAR
        $stockOut = ProductStock::where('product_id', $itemId)
            ->whereIn('stockable_type', [
                SaleDirect::class,
                Sale::class,
                Adjustment::class,
            ])
            ->where('status', 'minus')
            ->whereDate('created_at', $today)
            ->sum('qty');

        // YANG SUDAH DI CART
        $qtyInCart = SaleDirectCart::where('product_id', $itemId)
            ->whereDate('created_at', $today)
            ->sum('qty');

        // HITUNG SISA
        $totalAvailableQty = $stockIn - $stockOut - $qtyInCart;

        if ($totalAvailableQty <= 0) {
            LivewireAlert::title('Kesalahan!')
                ->text('Stock tidak mencukupi')
                ->error()
                ->toast()
                ->timer(3000)
                ->position('top-end')
                ->show();
            return false;
        }

        if ($existingItemIndex !== false) {
            $currentQty = $this->selectedItems[$existingItemIndex]['qty'];
            if ($totalAvailableQty <= $currentQty) {
                LivewireAlert::title('Kesalahan!')
                    ->text('Stock tidak mencukupi')
                    ->error()
                    ->toast()
                    ->timer(3000)
                    ->position('top-end')
                    ->show();
                return false;
            }

            $this->selectedItems[$existingItemIndex]['qty'] += 1;
        } else {
            if ($totalAvailableQty < 1) {
                LivewireAlert::title('Kesalahan!')
                    ->text('Stock tidak mencukupi')
                    ->error()
                    ->toast()
                    ->timer(3000)
                    ->position('top-end')
                    ->show();
                return false;
            }

            $item['qty'] = 1;
            $this->selectedItems[] = $item;
        }

        $outletId = $this->form['outlet'];
        $this->resulterProduct($outletId, $this->selectedItems);
        $this->updateGrandTotal();
    }


    public function updateQty($index, $qty)
    {
        if (!isset($this->selectedItems[$index])) {
            return;
        }

        $item = &$this->selectedItems[$index];
        $outletId = $this->form['outlet'];

        $product = Product::withSum([
            'productStockAdjustItems as stock_plus_qty' => function ($query) {
                $query->withoutGlobalScopes()
                    ->where('status', 'plus')
                    ->whereDate('created_at', Carbon::today());
            },
            'productStockAdjustItems as stock_minus_qty' => function ($query) {
                $query->withoutGlobalScopes()
                    ->where('status', 'minus')
                    ->whereDate('created_at', Carbon::today());
            }
        ], 'qty')
        ->where('id', $item['id'])
        ->whereHas('outlets', function ($query) use ($outletId) {
            $query->where('outlet_id', $outletId);
        })
        ->first();

        if (!$product) {
            return;
        }

        $stockPlus = (float) ($product->stock_plus_qty ?? 0);
        $stockMinus = (float) ($product->stock_minus_qty ?? 0);
        $availableStock = $stockPlus - $stockMinus;

        // Kurangi qty dari item lain yang sama di selectedItems
        foreach ($this->selectedItems as $i => $selected) {
            if ($i != $index && $selected['id'] == $item['id']) {
                $availableStock -= $selected['qty'];
            }
        }

        if ($qty > $availableStock) {
            $item['qty'] = max($availableStock, 0);

            LivewireAlert::title('Kesalahan!')
                ->text('Stock tidak mencukupi. Diubah ke jumlah maksimal yang tersedia.')
                ->error()
                ->toast()
                ->timer(3000)
                ->position('top-end')
                ->show();

            $this->resulterProduct($outletId, $this->selectedItems);
            $this->updateGrandTotal();

            return false;
        }

        // Update qty jika stok cukup
        $item['qty'] = $qty;
        $this->resulterProduct($outletId, $this->selectedItems);
        $this->updateGrandTotal();

        return true;
    }


    public function removeItem($itemId)
    {
        $this->selectedItems = array_filter($this->selectedItems, function ($item) use ($itemId) {
            return $item['id'] != $itemId;
        });
        $this->updateGrandTotal();
    }

    // protected function rules()
    // {
    //     $rules = (new ProductStoreRequest())->rules();

    //     if($this->categoryHasSub == 1 && count($this->subCategory)){
    //         $rules['form.sub_category_id'] = 'required';
    //     } else {
    //         $rules['form.sub_category_id'] = 'nullable';
    //     }
    //     return $rules;
    // }

    // protected function attributes()
    // {
    //     $attrs = (new ProductStoreRequest())->attributes();
    //     if($this->categoryHasSub == 1 && count($this->subCategory)){
    //         $attrs['form.sub_category_id'] = 'Sub Kategori';
    //     }

    //     return $attrs;
    // }

    // protected function messages(){

    //     $message = (new ProductStoreRequest())->messages();
    //     if($this->categoryHasSub == 1 && count($this->subCategory)){
    //         $rules['form.sub_category_id'] = ':attribute tidak boleh kosong';
    //     }

    //     return $message;
    // }

    // public function sub_category_changed($cat_id){
    //     $this->categoryHasSub = 1;

    //     $this->subCategory = Category::where(['parent_id' => $cat_id, 'deleted_at' => null])->get();
    // }

    public function updateDiscount()
    {
        if (count($this->selectedItems) == 0) {
            $this->alert('error', 'Belum ada item yang dipilih', [
                'position' => 'center'
            ]);

            $this->inv['discount'] = 0;
        } else {
            // dd($this->selectedItems);
            $this->updateGrandTotal(); // Panggil fungsi ini untuk memperbarui grand total setelah diskon diterapkan
        }
    }

    public function updateGrandTotal()
    {

        $subtotal = collect($this->selectedItems)->sum(function ($item) {
            return $item['qty'] * round($item['price']);
        });

        //  dd($subtotal);

        //     dd($this->selectedItems);


        $discount = isset($this->inv['discount']) ? (float) $this->inv['discount'] : 0;
        $subtotalAfterDiscount = $subtotal - $discount;


        $ppnRate = 0.11;
        $ppn = $subtotalAfterDiscount * $ppnRate;

        $this->subtotal = $subtotal;
        $this->grandTotal = $subtotalAfterDiscount + $ppn;
    }



    public function save()
    {
        // $this->validate(
        //     $this->rules(),
        //     $this->messages(),
        //     $this->attributes()
        // );
        $outletId =  $this->form['outlet'];
        if (count($this->selectedItems) == 0) {
            $this->alert('error', 'Belum ada item yang dipilih', [
                'position' => 'center'
            ]);
        } else {
            $this->inv['grand_total'] = $this->grandTotal;
            $this->inv['sub_total'] = $this->subtotal;

            if (isset($this->form['id']) && !empty($this->form['id'])) {
                if ($this->updateSale($this->inv, $this->selectedItems, $this->form['id'], $this->form['outlet']) == true) {
                    return redirect(route('poz::transaction.sale.index') . '?outlet=' . $outletId)->with('msg-sukses', "Data berhasil disimpan");
                } else {
                    return redirect(route('poz::transaction.sale.index') . '?outlet=' . $outletId)->with('msg-gagal', "Data gagal disimpan");
                }
            } else if ($this->storeSale($this->inv, $this->selectedItems, $this->form['outlet']) == true) {
                return redirect(route('poz::transaction.sale.index') . '?outlet=' . $outletId)->with('msg-sukses', "Data berhasil disimpan");
            } else {
                return redirect(route('poz::transaction.sale.index') . '?outlet=' . $outletId)->with('msg-gagal', "Data gagal disimpan");
            }
        }
    }

    public function render()
    {
        $data['warehouse'] = Warehouse::whereNull('deleted_at')->get();

        return view('poz::livewire.transaction.sale', $data);
    }
}
