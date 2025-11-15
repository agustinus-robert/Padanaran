<?php

namespace Modules\Poz\Http\Controllers\API;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\SaleDirect;
use Modules\Poz\Models\SaleDirectItems;
use Modules\Poz\Repositories\SaleDirectRepository;
use Modules\Poz\Models\ProductStock;
use Modules\Poz\Models\Purchase;
use Modules\Poz\Models\SaleDirectCart;
use Modules\Poz\Models\Adjustment;
use Modules\Poz\Models\Sale;
use Modules\Account\Models\UserToken;
use Illuminate\Http\Request;

class SaleApiController extends Controller
{
    use SaleDirectRepository;
    /**
     * Show the dashboard page.
     */
    private function cekStock($product_id, Request $request)
    {
        $getTokenUser = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $getTokenUser)->first();

        $stockIn = ProductStock::where([
            'product_id' => $product_id,
            'stockable_type' => Purchase::class
        ])->isStock()->sum('qty');

        $stockOut = ProductStock::where('product_id', $product_id)
            ->where(function ($query) {
                $query->where('stockable_type', SaleDirect::class)
                    ->orWhere('stockable_type', Sale::class);
            })->isStock()
            ->sum('qty');

        $stockAdjustment = ProductStock::where([
            'product_id' => $product_id,
            'stockable_type' => Adjustment::class
        ])->isStock()->get()->sum(function ($item) {
            $qty = abs($item->qty);
            return $item->status === 'minus' ? -$qty : $qty;
        });

        $qtyInCart = SaleDirectCart::where(['created_by' => $userToken->user_id, 'product_id' => $product_id])->sum('qty');

        $availableStock = $stockIn - $stockOut - $qtyInCart;

        return $availableStock;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $search = $request->get('search');
        $sortBy = $request->get('id');
        $order = $request->get('order');

        $status = $request->get('status');
        $saleDirectsQuery = SaleDirect::with('saleItems');

        if ($status) {
            // Jika ada status, tambahkan filter status
            $saleDirectsQuery->where('sale_status', $status);
        }

        if (!empty($search)) {
            $saleDirectsQuery->where('customer_name', 'LIKE', '%' . $search . '%')
                ->orWhere('desk_name', 'LIKE', '%' . $search . '%');
        }



        $user = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $user)->first();

        $saleDirectsQuery->where('created_by', $userToken->user_id);

        if (in_array(strtolower($order), ['asc', 'desc'])) {
            $saleDirectsQuery->orderBy('id', strtoupper($order));
        } else {
            $saleDirectsQuery->orderBy('id', 'desc');
        }

        $saleDirects = $saleDirectsQuery->paginate($perPage);

        return response()->json($saleDirects);
    }

    public function addItemDirect($transaction_id, Request $request)
    {
        if (SaleDirect::find($transaction_id)->sale_status == 1) {
            $response = $this->addDirectItems($request->all(), $transaction_id, $request->header('X-API-KEY'));

            if ($response instanceof \Illuminate\Http\JsonResponse) {
                return $response;
            }
        } else if (SaleDirect::find($transaction_id)->sale_status == 2) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, Transaksi ini telah dibatalkan'
            ], 400);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, Transaksi ini sudah selesai'
            ], 400);
        }

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan'
        ], 500);
    }

    public function deskCustDirects($transaction_id, Request $request)
    {
        if (SaleDirect::find($transaction_id)->sale_status == 1) {
            $response = $this->deskCustDirect($request->all(), $transaction_id, $request->header('X-API-KEY'));

            if ($response instanceof \Illuminate\Http\JsonResponse) {
                return $response;
            }
        } else if (SaleDirect::find($transaction_id)->sale_status == 2) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, Transaksi ini telah dibatalkan'
            ], 400);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, Transaksi ini sudah selesai'
            ], 400);
        }

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan'
        ], 500);
    }

    public function deleteItemDirect($transaction_id, Request $request)
    {
        if (SaleDirect::find($transaction_id)->sale_status == 1) {
            $response = $this->deleteDirectItems($request->all(), $transaction_id, $request->header('X-API-KEY'));

            if ($response instanceof \Illuminate\Http\JsonResponse) {
                return $response;
            }
        } else if (SaleDirect::find($transaction_id)->sale_status == 2) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, Transaksi ini telah dibatalkan'
            ], 400);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, Transaksi ini sudah selesai'
            ], 400);
        }

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan'
        ], 500);
    }

    public function updateQtyItemDirect($transaction_id, Request $request)
    {
        if (SaleDirect::find($transaction_id)->sale_status == 1) {
            $response = $this->updateQtyDirectItems($request->all(), $transaction_id, $request->header('X-API-KEY'));

            if ($response instanceof \Illuminate\Http\JsonResponse) {
                return $response;
            }
        } else if (SaleDirect::find($transaction_id)->sale_status == 2) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, Transaksi ini telah dibatalkan'
            ], 400);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, Transaksi ini sudah selesai'
            ], 400);
        }

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan'
        ], 500);
    }

    public function store(Request $request)
    {
        $response = $this->storeSale($request->all(), $request->header('X-API-KEY'));

        if ($response instanceof \Illuminate\Http\JsonResponse) {
            return $response; // Kembalikan langsung respons JSON dari storeSale
        }

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan'
        ], 500);
    }

    public function show($sale_api)
    {
        $saleDirect = SaleDirect::with('saleItems')->findOrFail($sale_api);
        $saleDirect->price = (int) $saleDirect->price;
        return response()->json($saleDirect);
    }

    public function edit($sale_api)
    {
        $saleDirect = SaleDirect::with('saleItems')->findOrFail($sale_api);
        return response()->json($saleDirect);
    }

    public function update($sale_api, Request $request)
    {
        $response = $this->updateSale($sale_api, $request->all(), $request->header('X-API-KEY'));

        if ($response instanceof \Illuminate\Http\JsonResponse) {
            return $response; // Kembalikan langsung respons JSON dari storeSale
        }

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan memperbarui data'
        ], 500);
    }

    public function destroy($sale_api, Request $request)
    {
        $response = $this->destroySale($sale_api, $request->header('X-API-KEY'));

        if ($response instanceof \Illuminate\Http\JsonResponse) {
            return $response; // Kembalikan langsung respons JSON dari storeSale
        }

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan'
        ], 500);
    }
}
