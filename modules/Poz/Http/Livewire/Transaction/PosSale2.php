<?php

namespace Modules\Poz\Http\Livewire\Transaction;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use Modules\Poz\Models\Product;
use Modules\Poz\Models\Category;
use Modules\Poz\Models\Brand;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Modules\Poz\Repositories\SaleRepository;
use Modules\Poz\Models\Sale as SaleData;
use Livewire\Attributes\On;
use Modules\Poz\Models\SaleItems;
use Modules\Poz\Models\ProductStock;
use Modules\Poz\Models\Purchase;
use Modules\Poz\Models\SaleDirectCart;
use Modules\Poz\Models\Adjustment;
use Modules\Poz\Models\SaleDirect;
use Modules\Account\Models\UserToken;
use Illuminate\Support\Facades\Auth;
use DB;

class PosSale2 extends Component
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
    public $subTotal = 0;
    public $products = [];
    public $filterCatValue = 0;
    public $filterBrandValue = 0;
    public $slot = 5;
    public $checkboxShortcut = [];
    public $cash = 0;

    private function productManage($outletId, $selectedItems = null)
    {
        $selectedItems = $selectedItems ?? $this->selectedItems;
        $today = now()->toDateString();
        // $this->cash = CashRegister::where('casier_id')
        $productz = Product::with([
                'productStockAdjustItems' => function ($query) use ($today) {
                    $query->withoutGlobalScopes()
                        ->whereDate('created_at', $today); // 🔒 hanya hari ini
                }
            ])
            ->where('name', 'ilike', '%' . $this->query . '%')
            // Hanya produk yang punya transaksi stok HARI INI
            ->whereHas('productStockAdjustItems', function ($query) use ($today) {
                $query->whereDate('created_at', $today);
            })
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            })
            ->get();

        $productz = $productz->map(function ($product) use ($selectedItems, $today) {
            $plus = $product->productStockAdjustItems
                ->where('status', 'plus')
                ->sum('qty');

            $minus = $product->productStockAdjustItems
                ->where('status', 'minus')
                ->sum('qty');

            $stock = (int) $plus - (int) $minus;

            foreach ($selectedItems as $item) {
                if ($item['id'] == $product->id) {
                    $stock -= (int) $item['qty'];
                }
            }

            $product->stock_qty = max($stock, 0); // minimal 0
            return $product;
        });

        return $productz;
    }

    public function mount($action, Request $req)
    {
        $this->inv['outlet'] = request()->query('outlet', auth()->user()->current_outlet_id);
        $outletId = $this->inv['outlet'];
        $id = $req->sale;
        $this->action = $action;
        $this->inv['ppn'] = '11%';
        $this->inv['discount'] = 0;
        $this->products = $this->productManage($outletId);
        $this->checkboxShortcut = Brand::where('is_shortcut', 1)
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            })
            ->pluck('id')->toArray();

        if (!empty($id) && is_string($id)) {
            $this->inv['id'] = $id;
            $this->action = 'Perbarui';
            $sale = SaleData::find($id);

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

            // Hitung ulang grand total
            //  $this->updateGrandTotal();


        } else {
            $this->action = 'Tambah';
            $digits = '0123456789';
            $randomNumbers = substr(str_shuffle(str_repeat($digits, 10)), 0, 10);
            $this->inv['code'] = $randomNumbers;
        }
    }

    #[On('productSaved')]
    public function closeModal()
    {
        // Logika untuk menutup modal
        $this->dispatch('close-modal');
    }


    public function filterReset()
    {
        $this->products = Product::whereNull('deleted_at')->get();
    }

    public function filterByCategory()
    {
        $this->selectedCategory = $this->filterCatValue;
        if ($this->filterCatValue == 0) {
            $this->products = Product::whereNull('deleted_at')->get();
        } else {
            $this->products = Product::where('category_id', $this->filterCatValue)->whereNull('deleted_at')->get();
        }
    }

    public function filterByBrand()
    {
        $this->selectedBrand = $this->filterBrandValue;
        if ($this->filterBrandValue == 0) {
            $this->products = Product::whereNull('deleted_at')->get();
        } else {
            $this->products = Product::where('brand_id', $this->filterBrandValue)->whereNull('deleted_at')->get();
        }
    }

    public function filterByBrandShortcut($id)
    {
        $this->selectedBrand = $id;
        $this->products = Product::where('brand_id', $id)->whereNull('deleted_at')->get();
    }

    public function updatedQuery()
    {
        // Jika query memiliki kurang dari 3 karakter, kosongkan hasil pencarian
        if (strlen($this->query) < 3) {
            $this->results = [];
            return;
        }

        // Lakukan pencarian dari database jika query lebih dari 3 karakter
        $this->results = Product::where('name', 'like', '%' . $this->query . '%') // Sesuaikan field yang ingin dicari
            ->limit(5)
            ->get()
            ->toArray();
    }

    // Fungsi untuk menambahkan item ke dalam tabel
    public function addItem($itemId)
    {
        $item = collect($this->results)->firstWhere('id', $itemId);
        if (empty($item)) {
            $resultLeft = Product::find($itemId);
            $item = collect($resultLeft);
        }

        $existingItemIndex = collect($this->selectedItems)->search(function ($selected) use ($itemId) {
            return $selected['id'] === $itemId;
        });

        if ($existingItemIndex !== false) {
            $this->selectedItems[$existingItemIndex]['qty'] += 1;
        } else {
            // Jika item belum ada, tambahkan item dengan qty awal 1
            $item['qty'] = 1;
            $this->selectedItems[] = $item;
        }

        $this->products = $this->productManage($this->inv['outlet'], $this->selectedItems);

        $this->updateGrandTotal();
    }

    public function updateQty($index, $qty)
    {
        // Update qty untuk item pada index yang diberikan
        $this->selectedItems[$index]['qty'] = $qty;
        $this->updateGrandTotal();
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
        $this->updateGrandTotal(); // Panggil fungsi ini untuk memperbarui grand total setelah diskon diterapkan
    }

    public function saveShortcut()
    {
        if (count($this->checkboxShortcut) > 4) {
            $this->alert('error', 'Shortcut tidak boleh lebih dari 4', [
                'position' => 'center'
            ]);
        } else {
            Brand::query()->update(['is_shortcut' => 0]);

            foreach ($this->checkboxShortcut as $key => $value) {
                $brand = Brand::find($value);
                if ($brand) {
                    $brand->is_shortcut = 1;
                    $brand->save();
                }
            }

            $this->dispatch('shortcutSaved');
        }
    }

    public function updateGrandTotal()
    {

        $subtotal = collect($this->selectedItems)->sum(function ($item) {
            return $item['qty'] * $item['price'];
        });


        $discount = isset($this->inv['discount']) ? (float) $this->inv['discount'] : 0;
        $subtotalAfterDiscount = $subtotal - $discount;

        // Terapkan PPN (contoh 11%)
        $ppnRate = 0.11; // 11% PPN
        $ppn = $subtotalAfterDiscount * $ppnRate;

        // Hitung grand total setelah diskon dan PPN
        $this->grandTotal = $subtotalAfterDiscount + $ppn;
        $this->subTotal = $subtotal;
    }


    public function applyDiscount()
    {
        if (count($this->selectedItems) == 0) {
            $this->alert('error', 'Belum ada item yang dipilih', [
                'position' => 'center'
            ]);
        } else {
            $this->updateGrandTotal();
        }
    }

    public function clearItem()
    {
        $this->selectedItems = [];
        $this->updateGrandTotal();
    }

    public function eraseProduct($productId){
        foreach ($this->selectedItems as $index => $item) {
            if ($item['id'] == $productId) {
                unset($this->selectedItems[$index]);
                break;
            }
        }

        $this->selectedItems = array_values($this->selectedItems);
        $this->products = $this->productManage($this->inv['outlet'], $this->selectedItems);
        $this->updateGrandTotal();
    }

    public function save()
    {
        // $this->validate(
        //     $this->rules(),
        //     $this->messages(),
        //     $this->attributes()
        // );

        if (count($this->selectedItems) == 0) {
            LivewireAlert::title('Kesalahan!')
                ->text('Belum ada Item yang dipilih')
                ->error()
                ->toast()
                ->timer(3000)
                ->position('top-end')
                ->show();
        } else {
            $this->inv['sale_date'] = now();
            $this->inv['sub_total'] = $this->subTotal;
            $this->inv['grand_total'] = $this->grandTotal;
            $this->inv['pos'] = 1;
            $this->inv['sale_status'] = 3;

            if (isset($this->inv['id']) && !empty($this->inv['id'])) {
                if ($this->updateSale($this->inv, $this->selectedItems, $this->inv['id']) == true) {
                    return redirect(route('poz::transaction.sale.index'))->with('msg-sukses', "Data berhasil disimpan");
                } else {
                    return redirect(route('poz::transaction.sale.index').'?outlet='. $this->form['outlet'])->with('msg-gagal', "Data gagal disimpan");
                }
            } else if ($result = $this->storeSale($this->inv, $this->selectedItems, (int) $this->inv['outlet'])) {
                if ($result['status'] == true) {
                    return redirect(route('poz::transaction.sale.pos-invoice', ['sale_id' => $result['sale_id']]) . '?outlet=' . $this->inv['outlet']);
                }
            } else {
                return redirect(route('poz::transaction.sale.index'))->with('msg-gagal', "Data gagal disimpan");
            }
        }
    }

    public function render()
    {
        $outletId = $this->inv['outlet'];

        $data['brand'] = Brand::whereNull('deleted_at')
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            })->get();

        $data['category'] = Category::whereNull('deleted_at')
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            })->get();

        $data['brand_shortcut'] = Brand::where('is_shortcut', 1)
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            })->get();

        return view('poz::livewire.transaction.posSale2', $data);
    }
}