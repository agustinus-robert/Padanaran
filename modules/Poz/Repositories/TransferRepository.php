<?php

namespace Modules\Poz\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\Transfer;
use Modules\Poz\Models\TransferItems;
use Modules\Poz\Models\PurchaseItems;
use DB;

trait TransferRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'reference',
        'transfer_status',
        'transfer_date',
        'transfer_delivered',
        'transfer_completed',
        'transfer_from_warehouse',
        'transfer_to_warehouse',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * Store newly created resource.
     */
    public function reduceProductStock($selected, $from_warehouse)
    {
        foreach ($selected as $item) {
            // Cari seluruh PurchaseItems yang terkait dengan product_id
            $purchaseItems = PurchaseItems::where(['product_id' => $item['id'], 'warehouse_id' => $from_warehouse])->get();

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

    public function storeTransfer(array $invoice, array $transferItem, $fromWarehouse, $toWarehouse)
    {
        DB::beginTransaction();

        try {
            $data['reference'] = 'REF' . '-' . rand();
            $data['transfer_status'] = $invoice['transfer_status'];
            $data['transfer_date'] = $invoice['transfer_date'];
            $data['transfer_from_warehouse'] = $invoice['transfer_from_warehouse'];
            $data['transfer_to_warehouse'] = $invoice['transfer_to_warehouse'];
            $data['created_by'] = \Auth::user()->id;

            $transfer = new Transfer(Arr::only($data, $this->keys));
            if ($transfer->save()) {
                foreach ($transferItem as $key => $value) {
                    $purchaseItems = new PurchaseItems();
                    $purchaseItems->transfer_id = $transfer->id;
                    $purchaseItems->warehouse_id = $toWarehouse;
                    $purchaseItems->product_id = $value['id'];
                    $purchaseItems->qty = $value['qty'];
                    $purchaseItems->created_by = \Auth::user()->id;
                    $purchaseItems->save();
                }
            }

            if ($invoice['transfer_status'] == 3) {
                if (!$this->reduceProductStock($transferItem, $fromWarehouse)) {
                    DB::rollback();
                    return false;
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
    public function updateTransfer(array $invoice, $purchaseItem, $id, $formWarehouse, $toWarehouse)
    {
        DB::beginTransaction();

        try {
            $data['transfer_status'] = $invoice['transfer_status'];
            $data['transfer_date'] = $invoice['transfer_date'];
            $data['transfer_from_warehouse'] = $invoice['transfer_from_warehouse'];
            $data['transfer_to_warehouse'] = $invoice['transfer_to_warehouse'];
            $data['updated_by'] = \Auth::user()->id;
            $transfer = Transfer::find($id);
            PurchaseItems::where('transfer_id', $id)->delete();

            if ($transfer->update(Arr::only($data, $this->keys))) {
                foreach ($purchaseItem as $key => $value) {
                    $purchaseItems = new PurchaseItems();
                    $purchaseItems->transfer_id = $id;
                    $purchaseItems->warehouse_id = $toWarehouse;
                    $purchaseItems->product_id = $value['id'];
                    $purchaseItems->qty = $value['qty'];
                    $purchaseItems->updated_by = \Auth::user()->id;
                    $purchaseItems->save();
                }
            }

            if ($data['transfer_status'] == 3) {
                if (!$this->reduceProductStock($purchaseItem, $formWarehouse)) {
                    DB::rollback();
                    return false;
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
    public function destroyTransfer($id)
    {
        if (Transfer::where('id', $id)->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreTransfer($id)
    {
        if (Transfer::onlyTrashed()->find($id)->restore()) {
            return true;
        }
        return false;
    }
}
