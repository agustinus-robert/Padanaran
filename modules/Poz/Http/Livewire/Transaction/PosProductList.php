<?php

namespace Modules\Poz\Http\Livewire\Transaction;

use Livewire\Component;
use Modules\Poz\Models\Sale;
use Modules\Poz\Models\Product;
use Modules\Poz\Models\Category;
use Modules\Poz\Models\Brand;
use Livewire\Attributes\On;
use Modules\Poz\Http\Livewire\Traits\ProductManageTrait;

class PosProductList extends Component
{
    use ProductManageTrait;

    public $products;
    public $selectedItems = [];
    public $tempSelectedItems = [];
    public $results = []; // Hasil pencarian
    public $inv = [];
    public $query = ''; // Query pencarian
    public $grandTotal = 0;
    public $subTotal = 0;
    public $defaultProducts = [];
    public $filterCatValue;
    public $filterBrandValue;
    public $queueItems;
    public $queueNotItems;
    public $selectedItemsBackup;


    public function mount($products, $inv)
    {
        $this->products = $products;
        $this->defaultProducts = $products; // simpan produk awal
        $this->inv = $inv;
    }

    // #[On('updatedQueues')]
    // public function handleQueueUpdated($queueItems)
    // {
    //     $this->queueItems = $queueItems;
    // }

    #[On('update-product-stock')]
    public function handleUpdateProductStock($payload)
    {
        $productId = $payload['productId'];
        $newQty = $payload['newQty'] ?? 0;

        // Pakai backup selectedItems kalau dikirim
        $this->selectedItems = $payload['selectedItemsBackup'] ?? $this->selectedItems;

        // Update qty di selectedItems
        if ($newQty > 0) {
            foreach ($this->selectedItems as &$item) {
                if ($item['id'] == $productId) {
                    $item['qty'] = $newQty;
                    break;
                }
            }
        } else {
            $this->selectedItems = array_values(
                array_filter($this->selectedItems, fn($item) => $item['id'] != $productId)
            );
        }

        // Sinkronkan queue
        $this->queueItems = $payload['existingQueue'] ?? $this->queueItems;

        // Rebuild products dengan data terbaru
        $this->products = $this->productManage(
            $this->inv['outlet'],
            $this->selectedItems,
            [],
            $this->queueItems
        );

        $this->updateGrandTotal();
    }







    public function updatedQuery()
    {
        $outletId = $this->inv['outlet'] ?? auth()->user()->current_outlet_id;


        $this->products = $this->productManage($this->inv['outlet'], $this->selectedItems, [], $this->queueItems);
    }


    #[On('selected-items-updated')]
    public function updateSelectedItems($newProducts, $newSelectedItems, $productQueue, $helper)
    {
        $this->selectedItems = collect($newSelectedItems)
            ->map(fn($item) => [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'qty' => $item['qty'] ?? 1
            ])
            ->unique('id')
            ->values()
            ->all();


        // $itemsForStock = array_merge($this->selectedItemsBackup ?? [], $this->selectedItems);
        // dump($this->selectedItems,
        //     $this->selectedItemsBackup,
        //     $this->queueItems
        //     );
        $this->products = $this->productManage($this->inv['outlet'], $this->selectedItems, [], $productQueue, $helper);

        $this->updateGrandTotal();
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


    public function filterByCategory()
    {
        $outletId = $this->inv['outlet'] ?? auth()->user()->current_outlet_id;

        $this->products = $this->productManage($this->inv['outlet'], $this->selectedItems, [
            'category_id' => $this->filterCatValue
        ], $this->queueItems);
    }

    public function filterByBrand()
    {
        $outletId = $this->inv['outlet'] ?? auth()->user()->current_outlet_id;

        $this->products = $this->productManage($outletId, $this->selectedItems, [
            'brand_id' => $this->filterBrandValue ?? 0,
            'category_id' => $this->filterCatValue ?? 0,
        ], $this->queueItems);
    }

    #[On('clear-selected-items')]
    public function clearList($queue)
    {
        $flatQueue = [];
        $merging = [];

        if(count($queue) > 0){
            foreach ($queue as $items) {
                if(count($items['sale_items']) > 0){
                    foreach ($items['sale_items'] as $item) {
                        $flatQueue[$items['student_id']][$item['product_id']] =
                            ($flatQueue[$items['student_id']][$item['product_id']] ?? 0) + $item['qty'];

                        $merging[] = [
                            'id' => $item['product_id'],
                            'qty' => $item['qty']
                        ];
                    }
                }
            }
        }

        $this->selectedItemsBackup = $merging;
        $this->selectedItems = [];
        $this->products = $this->productManage($this->inv['outlet'], [], [], $flatQueue);
    }

    #[On('delete-selected-items')]
    public function syncSelectedItems($queueTemp)
    {
        foreach ($queueTemp as $student_id => $items) {
            foreach ($items as $item) {
                foreach ($this->selectedItems as $key => $selected) {
                    if ($selected['id'] == $item['id']) {
                        $this->selectedItems[$key]['qty'] -= $item['qty'];

                        if ($this->selectedItems[$key]['qty'] <= 0) {
                            unset($this->selectedItems[$key]);
                        }
                    }
                }
            }
        }
        $newSelectedItems = array_values($this->selectedItems);

        $itemsForStock = $this->selectedItemsBackup ?? [];
        $this->products = $this->productManage($this->inv['outlet'], $this->tempSelectedItems, [], $this->queueItems, 'plus');
        $this->updateGrandTotal();
    }


    public function addItem($itemId)
    {
        // Bersihkan selectedItems dari duplikat lama
        $this->selectedItems = collect($this->selectedItems)
            ->unique('id')
            ->values()
            ->all();

        // Cek apakah item sudah ada di keranjang
        $existingItemIndex = collect($this->selectedItems)->search(function ($selected) use ($itemId) {
            return $selected['id'] === $itemId;
        });

        if ($existingItemIndex !== false) {
            $this->selectedItems[$existingItemIndex]['qty'] += 1;
        } else {
            $product = Product::find($itemId);
            if (!$product) return;

            $item = $product->toArray();
            // $backupQty = collect($this->selectedItemsBackup ?? [])
            // ->firstWhere('id', $itemId)['qty'] ?? 0;

            $item['qty'] = 1;
            $this->selectedItems[] = $item;
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


        // $this->products = $this->productManage($this->inv['outlet'], $this->selectedItems);
        $helper = [
          'status' =>  'first'
        ];

        // dump($existingQueue,
        //     $this->selectedItems
        // );

        $this->products = $this->productManage($this->inv['outlet'], $this->selectedItems, [], $existingQueue, $helper);
        $this->updateGrandTotal();
        $this->dispatch('selected-items-updated', $this->products, $this->selectedItems, $existingQueue, $helper);
    }

    #[On('update-stock-from-queue')]
    public function handleQueue($data)
    {
       // dd($queue);
        $flatQueue = [];
        $helper = [];

        foreach ($data['queue'] as $studentId => $items) {
            foreach ($items as $item) {
                $flatQueue[$studentId][$item['id']] =
                    ($flatQueue[$studentId][$item['id']] ?? 0) + $item['qty'];
            }
        }

        $this->queueItems = $flatQueue;

        if (isset($data['existingQueue']) && !empty($data['existingQueue'])) {
            $helper['status'] = 'plus';
            foreach ($data['existingQueue'] as $studentId => $exitData) {
                foreach ($exitData as $item) {
                    $helper[$studentId][] =
                        [
                            'id' => $item['id'],
                            'qty' => $item['qty']
                        ];
                }
            }
        }

        $outletId = $this->inv['outlet'];
        $this->products = $this->productManage($outletId, $this->selectedItems, [], $this->queueItems, $helper);

        foreach ($helper as $studentId => $items) {
            if ($studentId === 'status') continue;
            unset($this->queueItems[$studentId]);
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


        return view('poz::livewire.transaction.posProductList', $data);
    }
}
