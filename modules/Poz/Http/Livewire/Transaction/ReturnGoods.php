<?php

namespace Modules\Poz\Http\Livewire\Transaction;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use Modules\Poz\Models\Product;
use Modules\Poz\Models\ReturnGoods as Rgoods;
use Modules\Poz\Models\ReturnGoodsItems;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Modules\Poz\Repositories\ReturnRepository;
use DB;

class ReturnGoods extends Component
{
    use WithFileUploads, ReturnRepository;

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
            $retur = Rgoods::find($id);

            $this->form['code'] = $retur['reference'];
            $this->inv['sale_date'] = date('Y-m-d H:i:s', strtotime($retur['sale_date']));
            $this->inv['outlet_id'] = ReturnGoodsItems::where('return_id', $id)->first()->outlet_id;

            $this->selectedItems = $retur->returItems->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'name' => $item->product->name,
                    'qty' => $item->qty,
                    'price' => $item->product->price
                ];
            })->toArray();

            $this->grandTotal = $retur->grand_total;

            $this->inv['return_status'] = $retur->sale_status;
        } else {

            $this->action = 'Tambah';
            $digits = '0123456789';
            $randomNumbers = substr(str_shuffle(str_repeat($digits, 10)), 0, 10);
            $this->form['code'] = $randomNumbers;
        }

        $this->form['outlet'] = request()->query('outlet', auth()->user()->current_outlet_id);
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

        $this->results = Product::withSum('purchaseItems', 'qty')->where('name', 'ilike', '%' . $this->query . '%')
            ->whereHas('purchaseItems', function ($query) {
                $query->where('qty', '>', 0);
            })
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            })
            ->limit(5)
            ->get();

        //->toSql();

    }

    // Fungsi untuk menambahkan item ke dalam tabel
    public function addItem($itemId)
    {
        $item = collect($this->results->toArray())->firstWhere('id', $itemId);

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

        if (!isset($this->selectedItems[$index])) {
            return;
        }

        $item = &$this->selectedItems[$index];
        $outletId = $this->form['outlet'];

        $totalAvailableQty = Product::withSum('purchaseItems', 'qty')
            ->where('id', $item['id'])
            ->where('name', 'ilike', '%' . $this->query . '%')
            ->whereHas('purchaseItems', function ($query) {
                $query->where('qty', '>', 0);
            })
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            })
            ->first();

        if ($totalAvailableQty->purchaseItems->first()->qty < $qty) {
            $this->alert('error', 'Stock tidak mencukupi untuk jumlah yang diminta.', [
                'position' => 'center'
            ]);

            $this->selectedItems[$index]['qty'] = $totalAvailableQty;
            return false; // Menghentikan proses jika stok tidak mencukupi
        }

        // Jika stok cukup, update qty pada selectedItems
        $this->selectedItems[$index]['qty'] = $qty;
        //dd($this->selectedItems);
        // Update grand total setelah perubahan qty
        $this->updateGrandTotal();

        return true; // Mengembalikan true jika berhasil
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

            //$this->inv['discount'] = 0;
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


        $discount = 0;
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

        if (count($this->selectedItems) == 0) {
            $this->alert('error', 'Belum ada item yang dipilih', [
                'position' => 'center'
            ]);
        } else {
            $this->inv['grand_total'] = $this->grandTotal;
            $this->inv['sub_total'] = $this->subtotal;

            if (isset($this->form['id']) && !empty($this->form['id'])) {
                if ($this->updateReturn($this->inv, $this->selectedItems, $this->form['id'], $this->form['outlet']) == true) {
                    return redirect(route('poz::transaction.return.index'))->with('msg-sukses', "Data berhasil disimpan");
                } else {
                    return redirect(route('poz::transaction.return.index'))->with('msg-gagal', "Data gagal disimpan");
                }
            } else if ($this->storeReturn($this->inv, $this->selectedItems, $this->form['outlet']) == true) {
                return redirect(route('poz::transaction.return.index'))->with('msg-sukses', "Data berhasil disimpan");
            } else {
                return redirect(route('poz::transaction.return.index'))->with('msg-gagal', "Data gagal disimpan");
            }
        }
    }

    public function render()
    {
        return view('poz::livewire.transaction.return');
    }
}
