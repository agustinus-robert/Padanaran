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
use Modules\Poz\Models\CashRegister;
use DB;

class PosSale extends Component
{
    use WithFileUploads, SaleRepository;

    public $cash = 0;
    public $form = [];
    public $salesRef = [];
    public $inv = [];
    public $action;
    public $categoryHasSub = '';
    public $subCategory = [];
    public $query = ''; // Query pencarian
   // public $selectedItems = []; // Data yang dipilih oleh pengguna
    public $grandTotal = 0;
    public $subTotal = 0;
    public $products = [];
    public $filterCatValue = 0;
    public $filterBrandValue = 0;
    public $slot = 5;
    public $checkboxShortcut = [];
    public $moneyRegistration = 0;

    private function productManage($outletId, $selectedItems = null)
    {

         $this->cash = CashRegister::where('casier_id', auth()->user()->id)->exists()
        ? CashRegister::where('casier_id', auth()->user()->id)->sum('money')
        : 0;

        $today = now()->toDateString();

        $productz = Product::with([
                'productStockAdjustItems' => function ($query) use ($today) {
                    $query->withoutGlobalScopes()
                        ->whereDate('created_at', $today);
                }
            ])
            ->where('name', 'ilike', '%' . $this->query . '%')
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            })
            ->get();

        $productz = $productz->map(function ($product) {
            $plus = $product->productStockAdjustItems
                ->where('status', 'plus')
                ->sum('qty');

            $minus = $product->productStockAdjustItems
                ->where('status', 'minus')
                ->sum('qty');

            $stock = (int) $plus - (int) $minus;

            $product->stock_qty = max($stock, 0); // tetap minimal 0
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


    // Fungsi untuk menambahkan item ke dalam tabel
    

    // public function removeItem($itemId)
    // {
    //     $this->selectedItems = array_filter($this->selectedItems, function ($item) use ($itemId) {
    //         return $item['id'] != $itemId;
    //     });
    //     $this->updateGrandTotal();
    // }

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


    // public function clearItem()
    // {
    //     $this->selectedItems = [];
    //     $this->updateGrandTotal();
    // }

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

   public function applySaled()
    {
        $userId = auth()->user()->id;
        $amountToAdd = $this->moneyRegistration; // jumlah yang akan ditambah

        if (!$amountToAdd || $amountToAdd <= 0) {
            LivewireAlert::title('Gagal!')
                ->text('Jumlah saldo harus lebih dari 0')
                ->warning()
                ->show();
            return;
        }

        $cashRegister = CashRegister::firstOrCreate(
            ['casier_id' => $userId],
            ['money' => 0] // jika belum ada, buat baru dengan 0
        );

        $cashRegister->money += $amountToAdd;
        $cashRegister->save();

        // Simpan log
        $cashRegister->logCash()->create([
            'status' => 'plus',
            'money' => $amountToAdd,
        ]);

        // Reset input
        $this->moneyRegistration = null;

        LivewireAlert::title('Penambahan Saldo!')
            ->text('Saldo pada kasir telah ditambahkan')
            ->success()
            ->show();

        $this->dispatch('close-saldo-modal');

        // Refresh total cash
        $this->cash = CashRegister::where('casier_id', $userId)->sum('money');
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