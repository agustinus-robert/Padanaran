<?php

namespace Modules\Poz\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\Adjustment;
use Modules\Poz\Models\ProductStock;
use Modules\Poz\Models\ProductStockTemporary;

use Modules\Poz\Models\Product;
use Illuminate\Support\Str;

trait AdjustmentRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'product_id',
        'supplier_id',
        'status',
        'qty',
        'shift',
        'product_status'
    ];

    /**
     * Store newly created resource.
     */
    public function storeAdjustment(array $data, $outletId)
    {
        $adjustment = new Adjustment(Arr::only($data, $this->keys));
        // dd($data);
        if ($adjustment->save()) {
            if ($outletId) {
                $adjustment->outlets()->attach($outletId);
                
                $shiftMap = [
                    1 => 'morning',
                    2 => 'afternoon',
                    3 => 'evening',
                ];

                $isNotStock = null;
                if(isset($data['supplier_id'])){
                    $isNotStock = 1;                                    
                } 

                $productStock = ProductStock::create([
                    'product_id' => $data['product_id'],
                    'supplier_id' => $data['supplier_id'],
                    'stockable_id' => $adjustment->id,
                    'stockable_type' => \Modules\Poz\Models\Adjustment::class,
                    'status' => $data['status'],
                    'grand_total' => (Product::find($data['product_id'])->wholesale * $data['qty']),
                    'wholesale' => Product::find($data['product_id'])->wholesale,
                    'qty' => $data['qty'],
                    'shift' => $shiftMap[$data['shift']] ?? null,
                    'created_by' => auth()->id(),
                    'is_not_stock' => $isNotStock
                ]);

                $productStock->outlets()->syncWithoutDetaching($outletId);
                
            }

            return true;
        }

        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyInquiry($id)
    {
        if (Brand::where('id', $id)->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreInquiry($id)
    {
        if (Brand::onlyTrashed()->find($id)->restore()) {
            return true;
        }
        return false;
    }
}
