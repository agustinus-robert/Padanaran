<?php

namespace Modules\Poz\Http\Livewire\Transaction;

use Livewire\Component;
use Modules\Poz\Models\ProductStock;
use Modules\Poz\Models\SaleDirectCart;
use Modules\Poz\Models\Sale;
use Illuminate\Support\Facades\DB;
use Modules\Poz\Models\Brand;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\Sale as SaleData;
use Modules\Poz\Models\Product;
use Modules\Academic\Models\Student;
use Livewire\Attributes\On;
use Modules\Account\Models\UserToken;
use Modules\Poz\Repositories\SaleRepository;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use Modules\Poz\Models\CashRegister;
use Modules\Poz\Http\Livewire\Traits\ProductManageTrait;

class PosOrderSummary extends Component
{
    use WithFileUploads, SaleRepository, ProductManageTrait;

    public $selectedItems = [];
    public $products = [];
    public $inv = [];
    public $subTotal = 0;
    public $grandTotal = 0;
    public $query = '';
    public $cash = 0;
    public $canPay = false;
    public $queue = [];
    public $students = [];
    public $studentQueue = [];
    public $selectedQueue = [];
    public $selectedItemsBackup = [];
    public $queueItems = [];
    public $restore = false;
    public array $activeQueues = [];


    public function mount($products, $inv)
    {
        // $this->selectedItems = $selectedItems;
        $this->products = $products;
        $this->inv = $inv;
        $this->inv['returns'] = 0;
        $this->cash = CashRegister::where('casier_id', auth()->user()->id)->exists()
        ? CashRegister::where('casier_id', auth()->user()->id)->sum('money')
        : 0;
        $this->students = Student::whereNull('deleted_at')->get();
    }

    // #[On('updatedQueues')]
    // public function handleQueueUpdated($queueItems)
    // {
    //     $this->queueItems = $queueItems;
    // }

    #[On('set-backup-qty')]
    public function setBackupQty(?array $backup)
    {
        $this->selectedItemsBackup = $backup ?? [];
    }

    #[On('queue-sh')]
    public function loadExistingQueue()
    {
        $outlet_id = $this->inv['outlet'];
        $existingSales = Sale::with('saleItems')
            ->where('sale_status', 1)
            ->whereHas('outlets', function($q) use ($outlet_id) {
                $q->where('outlet_id', $outlet_id);
            })
            ->get();

        $this->studentQueue = [];
        foreach ($existingSales as $sale) {
            foreach ($sale->saleItems as $item) {
                $listing[$item->product_id] = [
                    'id' => $item->product_id,
                    'qty' => $item->qty,
                    'price' => $item->price
                ];
                $student = Student::find($sale->student_id);

                $this->studentQueue[$student->id] = [
                    'name' => $student->user->name,
                    'detail' => $listing ?? []
                ];
            }
        }
    }



   #[On('queue-loaded')]
    public function queueList($queue = [])
    {
        $this->studentQueue = [];

        if(count($queue) > 0){
            foreach($queue as $key => $listing){
                //foreach($listing as $value){
                    $student = Student::find($key);
                    if(!$student) continue;

                    $this->studentQueue[$student->id] = [
                        'name' => $student->user->name,
                        'detail' => $listing ?? []
                    ];
               // }
            }
        }
    }

    public function showSelectedQueue(){

        if (empty($this->queueItems)) {
            $this->queueItems = $this->getQueueItems();
        }

        $this->selectedItems = $this->studentQueue[$this->selectedQueue]['detail'];

        $this->activeQueues[$this->selectedQueue] = true;

        if (isset($this->queueItems[$this->selectedQueue])) {
            unset($this->queueItems[$this->selectedQueue]);
        }
    }

    public function updateDiscount()
    {
        $this->updateGrandTotal();
    }

    public function updateGrandTotal()
    {

        $subtotal = collect($this->selectedItems)->sum(function ($item) {
            return $item['qty'] * $item['price'];
        });


        $discount = isset($this->inv['discount']) ? (float) $this->inv['discount'] : 0;
        $subtotalAfterDiscount = $subtotal - $discount;

        $ppnRate = 0.11; // 11% PPN
        $ppn = $subtotalAfterDiscount * $ppnRate;

        $this->grandTotal = $subtotalAfterDiscount + $ppn;
        $this->subTotal = $subtotal;
    }


    public function applyDiscount()
    {
        if (count($this->selectedItems) == 0) {
            LivewireAlert::title('Kesalahan!')
                ->text('Item belum ada')
                ->error()
                ->toast()
                ->timer(3000)
                ->position('top-end')
                ->show();

        } else {
            $this->updateGrandTotal();
        }
    }

    public function applyReturns()
    {
        $this->updateGrandTotal();

        $totalPayment = $this->inv['returns'] ?? 0;

        if ($totalPayment < $this->grandTotal) {
            LivewireAlert::title('Kesalahan!')
                ->text('Uang dibayarkan kurang')
                ->error()
                ->toast()
                ->timer(3000)
                ->position('top-end')
                ->show();

            $this->inv['returns'] = 0;
        } else {
            $this->inv['returns'] = $totalPayment - $this->grandTotal;
        }
    }


    // public function clearItem()
    // {
    //     $this->selectedItems = [];
    //     $this->updateGrandTotal();
    // }

    #[On('selected-items-updated')]
    public function updateSelectedItems($newProducts, $newSelectedItems)
    {
        $this->canPay = false;
        $this->products = is_array($newProducts) ? $newProducts : [$newProducts];

        // Bersihkan selectedItems sepenuhnya, re-index, dan pastikan unik
        $this->selectedItems = collect($newSelectedItems)
            ->map(function($item) {
                $item['qty'] = $item['qty'] ?? 1; // pastikan qty ada
                return $item;
            })
            ->unique('id')
            ->values()
            ->all();

       // dd($this->selectedItems);
        $this->updateGrandTotal();
    }

    public function checkPaymentTotal()
    {
        if (empty($this->grandTotal)) {
            LivewireAlert::title('Kesalahan!')
                ->text('Total pembayaran masih kosong!')
                ->error()
                ->toast()
                ->timer(3000)
                ->position('top-end')
                ->show();

            $this->inv['paymentTotal'] = 0;
            $this->canPay = false;
        } else {
             if($this->inv['paymentTotal'] > $this->grandTotal){
                if((int) $this->cash > (int) $this->inv['paymentTotal']){
                    $this->inv['returns'] = $this->inv['paymentTotal'] - $this->grandTotal;
                    $this->canPay = true;
                } else {
                    LivewireAlert::title('Kesalahan!')
                    ->text('Uang kembalian sistem kurang, harap isi saldo kembalian!')
                    ->error()
                    ->toast()
                    ->timer(3000)
                    ->position('top-end')
                    ->show();

                    $this->canPay = false;
                }
            } else if($this->inv['paymentTotal'] < $this->grandTotal) {
                LivewireAlert::title('Kesalahan!')
                ->text('Uang pembayaran anda kurang!')
                ->error()
                ->toast()
                ->timer(3000)
                ->position('top-end')
                ->show();

                $this->inv['returns'] = 0;
                $this->canPay = false;
            } else if($this->inv['paymentTotal'] == $this->grandTotal){
                $this->inv['returns'] = 0;
                $this->canPay = true;
            }
        }
    }

    public function increaseQty($index, Request $request)
    {
        $getTokenUser = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $getTokenUser)->first();
        $itemId = $this->selectedItems[$index]['id'];
        // $this->getQueueItems();

        if (empty($this->activeQueues)) {
            $this->getQueueItems();
       }

        $outlet_id = $this->inv['outlet'];
        $existingSales = Sale::with('saleItems')
            ->where('sale_status', 1)
            ->whereHas('outlets', function ($q) use ($outlet_id) {
                $q->where('outlet_id', $outlet_id);
            })
            ->get();

        // Prepare data untuk JS
        $existingQueue = [];
        foreach ($existingSales as $sale) {
            foreach ($sale->saleItems as $item) {
                $existingQueue[$sale->student_id][] = [
                    'id' => $item->product_id,
                    'qty' => $item->qty
                ];
            }
        }

        $existingItemIndex = collect($this->selectedItems)->search(fn($selected) => $selected['id'] === $itemId);

        $today = now()->toDateString();

        $stockIn = ProductStock::where('product_id', $itemId)
            ->whereIn('stockable_type', [
                \Modules\Poz\Models\Purchase::class,
                \Modules\Poz\Models\Adjustment::class,
            ])
            ->where('status', 'plus')
            ->whereDate('created_at', $today)
            ->sum('qty');

        $stockOut = ProductStock::where('product_id', $itemId)
            ->whereIn('stockable_type', [
                \Modules\Poz\Models\SaleDirect::class,
                \Modules\Poz\Models\Sale::class,
                \Modules\Poz\Models\Adjustment::class,
            ])
            ->where('status', 'minus')
            ->whereDate('created_at', $today)
            ->sum('qty');

        $qtyInCart = SaleDirectCart::where('product_id', $itemId)
            ->whereDate('created_at', $today)
            ->sum('qty');

        $qtyInSelected = $existingItemIndex !== false
            ? $this->selectedItems[$existingItemIndex]['qty']
            : 0;

        $backupQty = collect($this->selectedItemsBackup ?? [])
            ->firstWhere('id', $itemId)['qty'] ?? 0;


        $totalQtyConsidered = $qtyInSelected + $backupQty;
        $availableStock = $stockIn - $stockOut - $qtyInCart;

        if ($availableStock <= $totalQtyConsidered) {
            LivewireAlert::title('Kesalahan!')
                ->text('Stock tidak mencukupi')
                ->error()
                ->toast()
                ->timer(3000)
                ->position('top-end')
                ->show();

            $this->products = $this->productManage($this->inv['outlet'], $this->selectedItems, [], $this->queueItems);
            //$this->products = $this->productManage($this->inv['outlet'], array_merge($this->selectedItemsBackup ?? [], $this->selectedItems ?? []));
            return;
        }

        // Tambahkan ke selectedItems
        if ($existingItemIndex !== false) {

            $this->selectedItems[$existingItemIndex]['qty'] += 1;
        } else {
            $product = Product::find($itemId);
            if (!$product) return;

            $item = $product->toArray();
            $item['qty'] = 1;
            $this->selectedItems[] = $item;
        }

        $this->updateGrandTotal();
        $this->products = $this->productManage($this->inv['outlet'], $this->selectedItems, [], $this->queueItems);


        $this->dispatch('update-product-stock', [
            'productId' => $itemId,
            'newQty' => $this->selectedItems[$existingItemIndex !== false ? $existingItemIndex : array_key_last($this->selectedItems)]['qty'],
            'selectedItemsBackup' => $this->selectedItems ?? [],
            'existingQueue' => $this->queueItems
        ]);
    }

    public function clearItem()
    {
        $this->selectedItems = [];
        $this->updateGrandTotal();
        $this->products = $this->productManage($this->inv['outlet'], $this->selectedItems);

        $this->dispatch('selected-items-updated', $this->products, $this->selectedItems);
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
                return redirect(route('poz::transaction.sale.index'. '?outlet=' . $this->inv['outlet']))->with('msg-gagal', "Data gagal disimpan");
            }
        }
    }

    private function getQueueItems()
    {
        if(!empty($this->queueItems)) {
            return $this->queueItems;
        }

        $outlet_id = $this->inv['outlet'];
        $existingSales = Sale::with('saleItems')
            ->where('sale_status', 1)
            ->whereHas('outlets', fn($q) => $q->where('outlet_id', $outlet_id))
            ->get();

        $flatQueue = [];
        foreach($existingSales as $sale){
            foreach($sale->saleItems as $item){
                $flatQueue[$sale->student_id][$item->product_id] = $item->qty;
            }
        }

        $this->queueItems = $flatQueue;
        return $this->queueItems;

        //return $flatQueue;
    }


    #[On('queueSv')]
    public function queueSave(?int $student_id)
    {
        if (count($this->selectedItems) == 0) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Belum ada item yang dipilih!'
            ]);
            return;
        }

        DB::transaction(function () use ($student_id) {
            $existingSales = Sale::where('student_id', $student_id)
                                ->where('sale_status', 1)
                                ->get();

            foreach ($existingSales as $sale) {
                $sale->saleItems()->delete();
                $sale->outlets()->detach();
                $sale->delete();
            }

            $sale = Sale::create([
                'reference' => 'REF-'.uniqid(),
                'student_id' => $student_id,
                'sale_status' => 1,
                'discount' => 0,
                'sub_total' => $this->subTotal,
                'grand_total' => $this->grandTotal,
            ]);

            $sale->outlets()->attach($this->inv['outlet']);

            foreach($this->selectedItems as $item){
                $sale->saleItems()->create([
                    'product_id' => $item['id'],
                    'qty' => $item['qty'],
                    'created_by' => auth()->user()->id
                ]);
            }
        });

        $outlet_id = $this->inv['outlet'];
        $existingSales = Sale::with('saleItems')
                            ->where('sale_status', 1)
                            ->whereHas('outlets', function($q) use ($outlet_id) {
                                $q->where('outlet_id', $outlet_id);
                            })
                            ->get();

        $flatQueue = [];
        foreach($existingSales as $sale){
            foreach($sale->saleItems as $item){
                $flatQueue[$sale->student_id][$item->product_id] = $item->qty;
            }
        }

        $this->queueItems = $flatQueue;
        $this->selectedItems = [];
        $this->dispatch('queue-loaded', $existingSales);
        $this->dispatch('clear-selected-items', $existingSales);

        LivewireAlert::title('Berhasil!')
            ->text('Item berhasil diantrikan')
            ->success()
            ->toast()
            ->timer(3000)
            ->position('top-end')
            ->show();
    }


    public function deleteQueue($student_id)
    {
        $queueTemp = [];
        $queueTempNot = [];

        $outlet_id = $this->inv['outlet'];
        $existingSales = Sale::with('saleItems')
            ->where('sale_status', 1)
            ->whereHas('outlets', function ($q) use ($outlet_id) {
                $q->where('outlet_id', $outlet_id);
            })
            ->get();

        // Prepare data untuk JS
        $existingQueue = [];
        foreach ($existingSales as $sale) {
            foreach ($sale->saleItems as $item) {
                $existingQueue[$sale->student_id][] = [
                    'id' => $item->product_id,
                    'qty' => $item->qty
                ];
            }
        }

        DB::transaction(function() use ($student_id, &$queueTemp, &$queueTempNot) {
            $existingSales = \Modules\Poz\Models\Sale::where('student_id', $student_id)
                                ->where('sale_status', 1)
                                ->get();

            foreach($existingSales as $sale){
                foreach($sale->saleItems as $item){
                    $queueTemp[$sale->student_id][] = [
                        'id' => $item->product_id,
                        'qty' => $item->qty
                    ];
                }
            }

            $existingNotSales = \Modules\Poz\Models\Sale::where('student_id', '!=', $student_id)
                                ->where('sale_status', 1)
                                ->get();

            foreach($existingNotSales as $notSale){
                foreach($notSale->saleItems as $itemN){
                    $queueTempNot[$notSale->student_id][] = [
                        'id' => $itemN->product_id,
                        'qty' => $itemN->qty
                    ];
                }
            }

            foreach ($existingSales as $sale) {
                $sale->saleItems()->delete();
                $sale->outlets()->detach();
                $sale->delete();
            }
        });

        $existingSalesAfter = Sale::with('saleItems')
            ->where('sale_status', 1)
            ->whereHas('outlets', function ($q) use ($outlet_id) {
                $q->where('outlet_id', $outlet_id);
            })
            ->get();

        $existingQueueAfter = [];
        foreach ($existingSalesAfter as $sale) {
            foreach ($sale->saleItems as $item) {
                $existingQueueAfter[$sale->student_id][] = [
                    'id' => $item->product_id,
                    'qty' => $item->qty,
                ];
            }
        }

        $this->dispatch('queue-loaded', $existingQueueAfter);
        $this->dispatch('update-stock-from-queue', ['queue' => $queueTemp, 'existingQueue' => $existingQueue, 'status' => 'plus']);
       // $this->dispatch('delete-selected-items', $queueTemp);

        LivewireAlert::title('Berhasil!')
                ->text('Order murid '.Student::find($student_id)->user->name.' berhasil dihapus')
                ->success()
                ->toast()
                ->timer(3000)
                ->position('top-end')
                ->show();
    }


    // $this->dispatch('delete-queue-item', ['queueKey' => $queueKey]);



    public function decreaseQty($index)
    {
        if (!isset($this->selectedItems[$index])) {
            return;
        }

        $itemId = $this->selectedItems[$index]['id'];

        if ($this->selectedItems[$index]['qty'] > 1) {
            $this->selectedItems[$index]['qty'] -= 1;
        } else {
            // Hapus semua item dengan ID yang sama
            $this->selectedItems = array_values(
                array_filter($this->selectedItems, fn($item) => $item['id'] != $itemId)
            );
        }

        if (empty($this->selectedItems)) {
            unset($this->activeQueues[$this->selectedQueue]);
            $this->queueItems = $this->getQueueItems();
        }

       // $queueItems = $this->getQueueItems();
        // Refresh produk & total
        $this->products = $this->productManage(
            $this->inv['outlet'],
            $this->selectedItems,
            [],
            $this->queueItems
        );

        $this->updateGrandTotal();

        $newQty = collect($this->selectedItems)->firstWhere('id', $itemId)['qty'] ?? 0;

        $this->dispatch('update-product-stock', [
            'productId' => $itemId,
            'newQty' => $newQty,
            'selectedItemsBackup' => $this->selectedItems ?? [],
            'existingQueue' => $this->queueItems
        ]);
    }





    // public function addItem($productId)
    // {
    //     // Dispatch event custom ke parent
    //     $this->dispatch('add-item', ['itemId' => $productId]);
    // }


    public function render()
    {
        return view('poz::livewire.transaction.posOrderSummary');
    }
}
