<?php

namespace Modules\Poz\Http\Livewire\Transaction;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use Modules\Poz\Models\Product;
use Modules\Poz\Models\Warehouse;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Modules\Poz\Repositories\TransferRepository;
use Modules\Poz\Models\Transfer as TransferData;
use Modules\Poz\Models\TransferItems as TransferItemsData;
use Modules\Poz\Models\PurchaseItems;
use DB;

class Transfer extends Component
{
    use WithFileUploads, TransferRepository;

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
        $id = $req->transfer;
        $this->action = $action;
        $this->inv['ppn'] = '11%';

        if (!empty($id) && is_string($id)) {
            $this->form['id'] = $id;
            $this->action = 'Perbarui';
            $transfer = TransferData::find($id);

            $purchaseTransfer = DB::table('purchase_items')->where('transfer_id', $id)->get();

            $this->selectedItems = $transfer->transferItems->map(function ($item) use ($purchaseTransfer) {
                return [
                    'id' => $item->product_id,
                    'name' => porductName($item->product_id)->name,
                    'qty' => $item->qty,
                ];
            })->toArray();

            $this->form['code'] = $transfer->reference;
            $this->inv['transfer_date'] = $transfer->transfer_date;
            $this->inv['transfer_from_warehouse'] = $transfer->transfer_from_warehouse;
            $this->inv['transfer_to_warehouse'] = $transfer->transfer_to_warehouse;
            $this->inv['transfer_status'] = $transfer->transfer_status;
            // Hitung ulang grand total
            //  $this->updateGrandTotal();


        } else {
            $this->action = 'Tambah';
            $digits = '0123456789';
            $randomNumbers = substr(str_shuffle(str_repeat($digits, 10)), 0, 10);
            $this->form['code'] = $randomNumbers;
        }
    }

    public function updatedQuery()
    {
        if (empty($this->inv['transfer_from_warehouse']) || empty($this->inv['transfer_to_warehouse'])) {
            $this->results = [];
            $this->alert('error', 'Gudang awal dan Gudang tujuan harus dipilih', [
                'position' => 'center'
            ]);

            $this->query = '';
            return;
        } else if ($this->inv['transfer_from_warehouse'] == $this->inv['transfer_to_warehouse']) {
            $this->results = [];
            $this->alert('error', 'Gudang awal dan Gudang tujuan harus berbeda', [
                'position' => 'center'
            ]);

            $this->query = '';
            return;
        }


        // Jika query memiliki kurang dari 3 karakter, kosongkan hasil pencarian
        if (strlen($this->query) < 3) {
            $this->results = [];
            return;
        }

        $this->results = Product::select('product.*')
            ->join('purchase_items', 'purchase_items.product_id', '=', 'product.id') // Join dengan tabel
            ->where('product.name', 'like', '%' . $this->query . '%') // Filter berdasarkan nama produk
            ->where('purchase_items.qty', '>', 0) // Pastikan stok di PurchaseItems lebih dari 0
            ->where('purchase_items.warehouse_id', $this->inv['transfer_from_warehouse'])
            ->groupBy('product.id') // Group berdasarkan produk untuk menghindari duplikasi
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function addItem($itemId)
    {
        // Cari item dari hasil pencarian berdasarkan ID
        $item = collect($this->results)->firstWhere('id', $itemId);

        if (!$item) {
            // Jika item tidak ditemukan dalam results, kembalikan false
            return false;
        }

        // Cek apakah item sudah ada di selectedItems
        $existingItemIndex = collect($this->selectedItems)->search(function ($selected) use ($itemId) {
            return $selected['id'] === $itemId;
        });

        // Cari total stok yang tersedia untuk item ini di PurchaseItems
        $purchaseItems = PurchaseItems::where(['product_id' => $itemId, 'warehouse_id' => $this->inv['transfer_from_warehouse']])->get();

        $totalAvailableQty = $purchaseItems->sum('qty'); // Total stok yang tersedia

        // Cek apakah stok yang tersedia cukup
        if ($totalAvailableQty <= 0) {
            // Jika stok habis
            $this->alert('error', 'Stock tidak mencukupi', [
                'position' => 'center'
            ]);
            return false; // Hentikan proses jika stok habis
        }

        if ($existingItemIndex !== false) {
            // Jika item sudah ada di selectedItems, cek apakah stok mencukupi
            $currentQty = $this->selectedItems[$existingItemIndex]['qty'];
            if ($totalAvailableQty <= $currentQty) {
                // Jika stok yang tersedia kurang dari qty yang ingin ditambahkan
                $this->alert('error', 'Jumlah stok tidak mencukupi', [
                    'position' => 'center'
                ]);
                return false; // Hentikan proses jika stok tidak cukup
            }

            // Jika stok cukup, tambahkan qty pada item yang ada
            $this->selectedItems[$existingItemIndex]['qty'] += 1;
        } else {
            // Jika item belum ada, cek apakah stok cukup untuk menambahkan item baru
            if ($totalAvailableQty < 1) {
                // Jika stok tidak cukup untuk menambah item pertama
                $this->alert('error', 'Jumlah stok tidak mencukupi', [
                    'position' => 'center'
                ]);
                return false; // Hentikan proses jika stok tidak cukup
            }

            // Jika stok cukup, tambahkan item baru dengan qty awal 1
            $item['qty'] = 1;
            $this->selectedItems[] = $item;
        }

        // Update grand total setelah menambah item
        return true; // Mengembalikan true jika berhasil
    }

    public function updateQty($index, $qty)
    {
        // Ambil item yang ingin diupdate berdasarkan index
        $item = $this->selectedItems[$index];

        // Cari PurchaseItems yang terkait dengan produk ini
        $purchaseItems = PurchaseItems::where(['product_id' => $item['id'], 'warehouse_id' => $this->inv['transfer_from_warehouse']])->get();
        $totalAvailableQty = $purchaseItems->sum('qty'); // Total stok yang tersedia

        // Cek apakah stok yang tersedia cukup untuk qty yang diupdate
        if ($totalAvailableQty < $qty) {
            // Jika stok tidak mencukupi, tampilkan peringatan
            $this->alert('error', 'Stock tidak mencukupi untuk jumlah yang diminta.', [
                'position' => 'center'
            ]);

            $this->selectedItems[$index]['qty'] = $totalAvailableQty;
            return false; // Menghentikan proses jika stok tidak mencukupi
        }

        // Jika stok cukup, update qty pada selectedItems
        $this->selectedItems[$index]['qty'] = $qty;

        // Update grand total setelah perubahan qty

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

        if (count($this->selectedItems) == 0) {
            $this->alert('error', 'Belum ada item yang dipilih', [
                'position' => 'center'
            ]);
        } else {
            $this->inv['grand_total'] = $this->grandTotal;

            if (isset($this->form['id']) && !empty($this->form['id'])) {
                if ($this->updateTransfer($this->inv, $this->selectedItems, $this->form['id'], $this->inv['transfer_from_warehouse'], $this->inv['transfer_to_warehouse']) == true) {
                    return redirect(route('poz::transaction.transfer.index'))->with('msg-sukses', "Data berhasil disimpan");
                } else {
                    return redirect(route('poz::transaction.transfer.index'))->with('msg-gagal', "Data gagal disimpan");
                }
            } else if ($this->storeTransfer($this->inv, $this->selectedItems, $this->inv['transfer_from_warehouse'], $this->inv['transfer_to_warehouse']) == true) {
                return redirect(route('poz::transaction.transfer.index'))->with('msg-sukses', "Data berhasil disimpan");
            } else {
                return redirect(route('poz::transaction.transfer.index'))->with('msg-gagal', "Data gagal disimpan");
            }
        }
    }

    public function render()
    {
        $data['warehouse'] = Warehouse::whereNull('deleted_at')->get();

        return view('poz::livewire.transaction.transfer', $data);
    }
}
