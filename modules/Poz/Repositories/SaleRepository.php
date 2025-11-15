<?php

namespace Modules\Poz\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\Sale;
use Modules\Poz\Models\SaleItems;
use Modules\Poz\Models\PurchaseItems;
use Modules\Poz\Models\ProductStock;
use Modules\Poz\Models\SupplierSchedule;
use Modules\Poz\Models\CashRegister;
use Illuminate\Support\Facades\DB;

trait SaleRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'reference',
        'customer_id',
        'sale_status',
        'sale_date',
        'discount',
        'sub_total',
        'grand_total',
        'pos',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * Store newly created resource.
     */
    public function reduceProductStock($selected, $outletForm)
    {
        foreach ($selected as $item) {
            // Cari seluruh PurchaseItems yang terkait dengan product_id
            $purchaseItems = PurchaseItems::where(['product_id' => $item['id'], 'outlet_id' => $outletForm])->get();

            $totalAvailableQty = $purchaseItems->sum('qty'); // Jumlahkan seluruh stok produk dengan product_id yang sama

            // Jika total stok kurang dari jumlah yang terjual, tampilkan error
            if ($totalAvailableQty < $item['qty']) {
                return false; // Jika stok tidak mencukupi, hentikan proses
            }

            // Mengurangi stok sesuai jumlah yang terjual
            $qtyToReduce = $item['qty'];

            foreach ($purchaseItems as $purchaseItem) {
                if ($qtyToReduce <= 0) break; // Jika stok sudah habis, keluar dari loop

                $availableQty = $purchaseItem->qty;
                $reduceQty = min($qtyToReduce, $availableQty); // Kurangi sesuai dengan stok yang tersedia

                // Mengurangi stok pada PurchaseItem
                $purchaseItem->qty -= $reduceQty;
                $purchaseItem->save(); // Simpan perubahan

                $qtyToReduce -= $reduceQty; // Kurangi jumlah yang masih perlu dikurangi
            }
        }

        return true;
    }

    public function storeSale(array $invoice, array $saleItem, $outletForm)
    {
        DB::beginTransaction();

        try {
            $data['customer_id'] = null;
            $data['reference'] = 'REF' . '-' . rand();
            $data['sub_total'] = $invoice['sub_total'];
            $data['sale_date'] = (isset($invoice['sale_date']) ? date('Y-m-d') : $invoice['sale_date']);
            $data['discount'] = $invoice['discount'];
            $data['sale_status'] = $invoice['sale_status'];
            $data['grand_total'] = $invoice['grand_total'];
            $data['created_by'] = Auth::user()->id;

            if (isset($invoice['pos'])) {
                $data['pos'] = $invoice['pos'];
            }

            $sale = new Sale(Arr::only($data, $this->keys));
            if ($sale->save()) {

                if ($outletForm) {
                    $sale->outlets()->attach($outletForm);
                }

                foreach ($saleItem as $key => $value) {
                    $saleItems = new SaleItems();
                    $saleItems->sale_id = $sale->id;
                    $saleItems->product_id = $value['id'];
                    $saleItems->qty = $value['qty'];
                    $saleItems->created_by = Auth::user()->id;
                    $saleItems->save();

                    if ($invoice['sale_status'] == 3) {
                        $supplier = SupplierSchedule::with('supplier')->where('product_id', $value['id'])->first();

                        //$supplier = PurchaseItems::with('purchase')->where('product_id', $value['id'])->first();

                        $productStock = ProductStock::create([
                            'product_id' => $value['id'],
                            'supplier_id' => $supplier->supplier->id,
                            'stockable_id' => $sale->id,
                            'stockable_type' => \Modules\Poz\Models\Sale::class,
                            'status' => 'minus',
                            'grand_total' => (productItem($value['id'])->price * $value['qty']),
                            'wholesale' => productItem($value['id'])->wholesale,
                            'pricesale' => (productItem($value['id'])->price),
                            'qty' => $value['qty'],
                            'created_by' => \Auth::user()->id,
                        ]);

                        $productStock->outlets()->syncWithoutDetaching($outletForm);
                    }
                }
            }

             if (!empty($invoice['returns']) && $invoice['returns'] > 0) {
                $cashRegister = CashRegister::firstOrCreate(
                    ['casier_id' => Auth::user()->id],
                    ['money' => 0]
                );

                $cashRegister->logCash()->create([
                    'status' => 'minus',
                    'money' => $invoice['returns'],
                ]);

                $cashRegister->money -= $invoice['returns'];
                $cashRegister->save();
            }


            DB::commit();
            if (isset($invoice['pos']) && $invoice['pos'] == 1) {
                return [
                    'sale_id' => $sale->id,
                    'status' => true
                ];
            } else {
                return true;
            }
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    /**
     * Update the current resource.
     */
    public function updateSale(array $data, $saleItem, $id, $outletForm)
    {
        DB::beginTransaction();

        try {
            $data['customer_id'] = null;
            $data['discount'] = $data['discount'];
            $data['sale_date'] = $invoice['sale_date'];
            $data['sub_total'] = $invoice['sub_total'];
            $data['discount'] = $invoice['discount'];
            $data['sale_status'] = $invoice['sale_status'];
            $data['grand_total'] = $invoice['grand_total'];
            $data['updated_by'] = Auth::user()->id;
            $sale = Sale::find($id);

            SaleItems::where('sale_id', $id)->delete();

            if ($sale->update(Arr::only($data, $this->keys))) {
                if ($outletForm) {
                    $sale->outlets()->attach($outletForm);
                }

                foreach ($saleItem as $key => $value) {
                    $saleItems = new SaleItems();
                    $saleItems->sale_id = $sale->id;
                    $saleItems->product_id = $value['id'];
                    $saleItems->outlet_id = $outletForm;
                    $saleItems->qty = $value['qty'];
                    $saleItems->created_by = \Auth::user()->id;
                    $saleItems->save();
                }
            }

            if ($invoice['sale_status'] == 3) {
                if (!$this->reduceProductStock($saleItem, $outletForm)) {
                    DB::rollback();
                    return 'stock';
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    /**
     * Remove the current resource.
     */
    public function destroySale($id)
    {
        if (Sale::where('id', $id)->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreSale($id)
    {
        if (Sale::onlyTrashed()->find($id)->restore()) {
            return true;
        }
        return false;
    }
}
