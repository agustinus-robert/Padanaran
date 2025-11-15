<?php

namespace Modules\Poz\Http\Livewire\Transaction;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use Modules\Poz\Models\Product;
use Modules\Poz\Models\Supplier;
use Modules\Poz\Models\Warehouse;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Modules\Poz\Repositories\PurchaseRepository;
use Modules\Poz\Models\Purchase as PurchaseData;
use Modules\Poz\Models\PurchaseItems as PurchaseItemsData;
use DB;

class Purchase extends Component
{
    use WithFileUploads, PurchaseRepository;

    public $supplier = [];
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

    public function mount($action, Request $req)
    {
        $id = $req->purchase;
        $this->action = $action;
        $this->inv['ppn'] = '11%';
        // where null
        $this->supplier = Supplier::whereNull('deleted_at')->get();

        if (!empty($id) && is_string($id)) {
            $this->form['id'] = $id;
            $this->action = 'Perbarui';
            $purchase = PurchaseData::find($id);

            $this->selectedItems = $purchase->purchaseItems->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'name' => $item->product->name,
                    'qty' => $item->qty,
                    'price' => $item->product->price
                ];
            })->toArray();

            $this->form['code'] = $purchase->reference;
            $this->grandTotal = $purchase->grand_total;
            $this->inv['purchase_date'] = $purchase->purchase_date;
            $this->inv['discount'] = $purchase->discount;
            $this->inv['warehouse_id'] = $purchase->warehouse_id;
            $this->inv['purchase_status'] = $purchase->purchase_status;
            $this->inv['supplier_id'] = $purchase->supplier_id;
            // Hitung ulang grand total
            //  $this->updateGrandTotal();


        } else {
            $this->action = 'Tambah';
            $digits = '0123456789';
            $randomNumbers = substr(str_shuffle(str_repeat($digits, 10)), 0, 10);
            $this->form['code'] = $randomNumbers;
        }

        $this->inv['outlet'] = request()->query('outlet', auth()->user()->current_outlet_id);
    }

    public function updatedQuery()
    {

        if (strlen($this->query) < 3) {
            $this->results = [];
            return;
        }

        if (empty($this->inv['supplier_id'])) {
            LivewireAlert::title('Kesalahan!')
                ->text('Pilih Dahulu supplier')
                ->error()
                ->toast()
                ->timer(3000)
                ->position('top-end')
                ->show();

            $this->query = '';
            $this->results = [];
            return;
        }

        $outletId = $this->inv['outlet'];

        $this->results = Product::where('name', 'like', '%' . $this->query . '%')
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            })
            ->limit(5)
            ->get()
            ->toArray();


    }

    public function addItem($itemId)
    {
        $item = collect($this->results)->firstWhere('id', $itemId);

        $existingItemIndex = collect($this->selectedItems)->search(function ($selected) use ($itemId) {
            return $selected['id'] === $itemId;
        });

        if ($existingItemIndex !== false) {
            $this->selectedItems[$existingItemIndex]['qty'] += 1;
        } else {
            $item['qty'] = 1;
            $this->selectedItems[] = $item;
        }

        $this->updateGrandTotal();
    }

    public function updateQty($index, $qty)
    {
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
        if (count($this->selectedItems) == 0) {
            LivewireAlert::title('Kesalahan!')
                ->text('Belum ada item dipilih!')
                ->error()
                ->timer(3000) // Dismisses after 3 seconds
                ->show();

            $this->inv['discount'] = 0;
        } else {
            $this->updateGrandTotal(); // Panggil fungsi ini untuk memperbarui grand total setelah diskon diterapkan
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
    }

    public function save()
    {
        // $this->validate(
        //     $this->rules(),
        //     $this->messages(),
        //     $this->attributes()
        // );
        $outletId =  $this->inv['outlet'];
        if (count($this->selectedItems) == 0) {
            LivewireAlert::title('Kesalahan!')
                ->text('Belum ada item dipilih!')
                ->error()
                ->timer(3000)
                ->show();
        } else {
            $this->inv['grand_total'] = $this->grandTotal;

            if (isset($this->form['id']) && !empty($this->form['id'])) {
                if ($this->updatePurchase($this->inv, $this->selectedItems, $this->form['id']) == true) {
                    return redirect(route('poz::transaction.purchase.index') . '?outlet=' . $outletId)->with('msg-sukses', "Data berhasil disimpan");
                } else {
                    return redirect(route('poz::transaction.purchase.index') . '?outlet=' . $outletId)->with('msg-gagal', "Data gagal disimpan");
                }
            } else if ($this->storePurchase($this->inv, $this->selectedItems) == true) {
                return redirect(route('poz::transaction.purchase.index') . '?outlet=' . $outletId)->with('msg-sukses', "Data berhasil disimpan");
            } else {
                return redirect(route('poz::transaction.purchase.index') . '?outlet=' . $outletId)->with('msg-gagal', "Data gagal disimpan");
            }
        }
    }

    public function render()
    {
        $data['warehouse'] = Warehouse::whereNull('deleted_at')->get();

        return view('poz::livewire.transaction.purchase', $data);
    }
}
