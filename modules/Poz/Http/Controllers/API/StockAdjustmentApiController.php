<?php

namespace Modules\Poz\Http\Controllers\API;

use Modules\Reference\Http\Controllers\Controller;
use Modules\Poz\Models\Purchase;
use Modules\Poz\Models\SaleDirect;
use Modules\Poz\Models\Sale;
use Modules\Poz\Models\Product;
use Modules\Poz\Models\Adjustment;
use Modules\Poz\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Models\UserToken;
use Modules\Poz\Models\SupplierSchedule;
use Modules\Poz\Repositories\StockAdjustmentRepository;
use Modules\Poz\Models\Casier;
use Carbon\Carbon;
use Modules\Core\Enums\SupplierWorkEnum;

class StockAdjustmentApiController extends Controller
{
    use StockAdjustmentRepository;

    private function cekStock($product_id)
    {
        $stockIn = ProductStock::where([
            'product_id' => $product_id,
            'stockable_type' => Purchase::class
        ])->isStock()->sum('qty');

        $stockOut = ProductStock::where('product_id', $product_id)
            ->where(function ($query) {
                $query->where('stockable_type', SaleDirect::class)
                    ->orWhere('stockable_type', Sale::class);
            })->isStock()->sum('qty');

        $stockAdjustment = ProductStock::where([
            'product_id' => $product_id,
            'stockable_type' => Adjustment::class
        ])->isStock()->get()->sum(function ($item) {
            $qty = abs($item->qty);
            return $item->status === 'minus' ? -$qty : $qty;
        });

        $availableStock = $stockIn - $stockOut + $stockAdjustment;

        return $availableStock;
    }

   public function showSupplier($supplier_id, Request $request)
    {
        $getTokenUser = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $getTokenUser)->first();

        $shiftOrder = ['morning', 'afternoon', 'evening'];

        $schedules = SupplierSchedule::where('supplier_id', $supplier_id)
            ->get()
            ->map(function ($item) {
                $enum = SupplierWorkEnum::fromKey($item->time);

                return [
                    'product_id' => $item->product_id,
                    'shift'      => $item->time,
                    'data'       => [
                        'id'          => $item->id,
                        'supplier_id' => $item->supplier_id,
                        'product_id'  => $item->product_id,
                        'name'        => $item->product->name,
                        'time'        => $item->time,
                        'start_time'  => $enum?->startTime(),
                        'end_time'    => $enum?->endTime(),
                    ]
                ];
            })
            ->groupBy('product_id')
            ->map(function ($productGroup) use ($shiftOrder) {
                $grouped = $productGroup->groupBy('shift')
                    ->map(function ($shiftGroup) {
                        return $shiftGroup->pluck('data')->first();
                    });

                // Susun ulang shift sesuai urutan morning → afternoon → evening
                $sorted = collect($shiftOrder)
                    ->filter(fn($shift) => $grouped->has($shift))
                    ->mapWithKeys(fn($shift) => [$shift => $grouped[$shift]]);

                return $sorted;
            });

        return response()->json([
            'status' => true,
            'data'   => $schedules
        ]);
    }



    public function show($product_id, Request $request)
    {
        $getTokenUser = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $getTokenUser)->first();

        $supplier_id = $request->input('supplier_id');

        $query = SupplierSchedule::where('product_id', $product_id);

        if ($supplier_id) {
            $query->where('supplier_id', $supplier_id);
        }

        $schedules = $query->get();

        if ($schedules->isEmpty()) {
            return response()->json([
                'status' => false,
                'data' => 'produk ini tidak memiliki supplier'
            ]);
        }

        $result = $schedules->map(function ($item) {
            $enum = SupplierWorkEnum::fromKey($item->time);

            return [
                'id'          => $item->id,
                'supplier_id' => $item->supplier_id,
                'product_id'  => $item->product_id,
                'name'        => $item->product->name,
                'supplier_name' => $item->supplier->name,
                'time'        => $item->time,
                'start_time'  => $enum?->startTime(),
                'end_time'    => $enum?->endTime(),
            ];
        });

        return response()->json([
            'status' => true,
            'data'   => $result
        ]);
    }



    public function store(Request $request)
    {
        $getTokenUser = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $getTokenUser)->first();
        $id = $request->product_id;
        $qty = $request->qty;
        $type = $request->type;
        $wholesale = $request->wholesale;
        $price = $request->price;
        $supplier_id = $request->supplier_id;
        $times = $request->time;


        $production = Product::with('schedule')->find($id);
        
        if (!$production) {
            return response()->json([
                'status' => false,
                'message' => 'Produk tidak ditemukan, pada shift.'
            ], 404);
        }

        $todaySchedule = $production->schedule;
        $now = Carbon::now()->format('H:i');

        $timesLower = strtolower($times);
        $targetSupplierId = $supplier_id;

        $hasShiftAndSupplier = $production->schedule
            ->contains(function ($item) use ($timesLower, $targetSupplierId) {
                return strtolower($item->time) === $timesLower
                    && $item->supplier_id == $targetSupplierId;
            });


        if (!$hasShiftAndSupplier) {
            return response()->json([
                'status' => false,
                'message' => 'Stok tidak dapat ditambahkan karena shift tidak ada.'
            ], 400);
        }

        // if ($todaySchedule->isEmpty()) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Produk ini tidak memiliki jadwal supplier yang aktif pada hari ini.'
        //     ], 422);
        // }

        
        $buyOn = $production->wholesale;
        $sellOn = $production->price;
        //$times = $lastEndTime
        if(!empty($wholesale)){
            $buyOn = $wholesale;
        }

        if(!empty($price)){
            $sellOn = $price;
        }

        return $this->changeAdjustment($id, $qty, $type, $userToken->user_id, $buyOn, $sellOn, $times, $supplier_id);
    }
}
