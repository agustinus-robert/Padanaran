<?php

namespace Modules\Poz\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\Purchase;
use Modules\Poz\Models\ProductStock;
use Modules\Poz\Models\PurchaseItems;
use DB;

trait PurchaseRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'reference',
        'supplier_id',
        'purchase_status',
        'outlet_id',
        'discount',
        'grand_total',
        'purchase_date',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * Store newly created resource.
     */
    public function storePurchase(array $invoice, array $purchaseItem)
    {
        //   dd($invoice);
        DB::beginTransaction();

        try {
            $data['customer'] = 1;
            $data['reference'] = 'REF' . '-' . rand();
            $data['supplier_id'] = $invoice['supplier_id'];
            $data['purchase_status'] = $invoice['purchase_status'];
            $data['discount'] = $invoice['discount'];
            $data['grand_total'] = $invoice['grand_total'];
            $data['purchase_date'] = $invoice['purchase_date'] ?? now();
            $data['outlet_id'] = $invoice['outlet'];
            $data['created_by'] = \Auth::user()->id;

            $purchase = new Purchase(Arr::only($data, $this->keys));
            if ($purchase->save()) {
                if ($data['outlet_id']) {
                    $purchase->outlets()->attach($data['outlet_id']);
                }

                foreach ($purchaseItem as $key => $value) {
                    $purchaseItems = new PurchaseItems();
                    $purchaseItems->purchase_id = $purchase->id;
                    $purchaseItems->product_id = $value['id'];
                    $purchaseItems->qty = $value['qty'];
                    $purchaseItems->created_by = \Auth::user()->id;
                    $purchaseItems->save();

                    $productStock = ProductStock::create([
                        'product_id' => $value['id'],
                        'supplier_id' => $invoice['supplier_id'],
                        'stockable_id' => $purchase->id,
                        'stockable_type' => \Modules\Poz\Models\Purchase::class,
                        'status' => 'plus',
                        'grand_total' => (productItem($value['id'])->price * $value['qty']),
                        'wholesale' => productItem($value['id'])->wholesale,
                        'pricesale' => (productItem($value['id'])->price),
                        'qty' => $value['qty'],
                        'created_by' => \Auth::user()->id,
                    ]);

                    $productStock->outlets()->syncWithoutDetaching($data['outlet_id']);
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
     * Update the current resource.
     */
    public function updatePurchase(array $invoice, $purchaseItem, $id)
    {
        DB::beginTransaction();

        try {
            $data['supplier_id'] = $invoice['supplier_id'];
            $data['purchase_status'] = $invoice['purchase_status'];
            $data['discount'] = $invoice['discount'];
            $data['grand_total'] = $invoice['grand_total'];
            $data['purchase_date'] = $invoice['purchase_date'];
            $data['outlet_id'] = $invoice['outlet'];

            $data['updated_by'] = \Auth::user()->id;
            $purchase = Purchase::find($id);

            PurchaseItems::where('purchase_id', $id)->delete();

            if ($purchase->update(Arr::only($data, $this->keys))) {
                if ($data['outlet']) {
                    $purchase->outlets()->attach($data['outlet']);
                }

                foreach ($purchaseItem as $key => $value) {
                    $purchaseItems = new PurchaseItems();
                    $purchaseItems->purchase_id = $purchase->id;
                    $purchaseItems->product_id = $value['id'];
                    $purchaseItems->outlet_id = $invoice['outlet'];
                    // $purchaseItems->warehouse_id = $invoice['warehouse_id'];
                    $purchaseItems->qty = $value['qty'];
                    $purchaseItems->created_by = \Auth::user()->id;
                    $purchaseItems->save();
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
