<?php

namespace Modules\Poz\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\ProductStock;
use Modules\Poz\Models\Product;
use Modules\Poz\Models\Adjustment;
use Modules\Poz\Models\Casier;
use Modules\Poz\Models\Supplier;
use Modules\Poz\Models\SupplierSchedule;
use Modules\Core\Enums\SupplierWorkEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait StockAdjustmentRepository
{
    public function changeAdjustment($id, $qty, $type, $token, $buyOn, $sellOn, $times = null, $supplier_id)
    {
        if(empty($id)){
            return response()->json([
                'status' => false,
                'message' => 'Produk masih belum dipilih'
            ], 400);
        }

        if ($type === 'minus' && $qty > $this->cekStock($id)) {
            return response()->json([
                'status' => false,
                'message' => 'Barang keluar melebihi stok yang tersedia',
                'available_stock' => $this->cekStock($id)
            ], 400);
        }


        if ($qty > 0) {
            if ($type === 'plus') {
                $adjustmentType = 'plus';
            } elseif ($type === 'minus') {
                $adjustmentType = 'minus';
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tipe penyesuaian stock tidak valid'
                ], 400);
            }

            $enumWorking = SupplierWorkEnum::fromKey($times);

            $supplier = SupplierSchedule::with('supplier')->where('product_id', $id)->first();

            if(empty($supplier_id)){
                return response()->json([
                    'status' => false,
                    'message' => 'Supplier belum ada untuk produk ini'
                ], 400);
            }

            $adjustment = Adjustment::create([
                'product_id' => $id,
                'supplier_id' => $supplier_id,
                'status' => $type,
                'qty' => $qty,
                'shift'  => $enumWorking->value
            ]);

            $casier = Casier::where('user_id', $token)->first();
            $adjustment->outlets()->syncWithoutDetaching($casier->outlet_id);

            Product::where('id', $id)->update(['price' => $sellOn, 'wholesale' => $buyOn]);
            ProductStock::create([
                'product_id' => $id,
                'supplier_id' => $supplier_id,
                'stockable_type' => \Modules\Poz\Models\Adjustment::class,
                'stockable_id' => $adjustment->id,
                'qty' => $qty,
                'grand_total' => ($buyOn * $qty),
                'status' => $adjustmentType,
                'wholesale' => $buyOn,
                'pricesale' => $sellOn,
                'shift' => $times,
                'created_by' => $token
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Stok berhasil disesuaikan',
                'available_stock' => $this->cekStock($id)
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Quantity harus lebih dari 0'
            ], 400);
        }

    }
}
