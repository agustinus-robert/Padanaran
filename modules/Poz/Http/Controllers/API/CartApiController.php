<?php

namespace Modules\Poz\Http\Controllers\API;

use Modules\Reference\Http\Controllers\Controller;
use Modules\Poz\Models\SaleDirectCart;
use Modules\Poz\Models\SaleDirectCustomerDesk;
use Modules\Poz\Models\Purchase;
use Modules\Poz\Models\SaleDirect;
use Modules\Poz\Models\Sale;
use Modules\Poz\Models\Adjustment;
use Modules\Account\Models\UserToken;
use Modules\Poz\Repositories\SaleDirectCartRepository;
use Modules\Poz\Models\ProductStock;

use Illuminate\Http\Request;

class CartApiController extends Controller
{
    use SaleDirectCartRepository;
    /**
     * Show the dashboard page.
     */
    // public function index(Request $request)
    // {
    //     return response()->json($saleDirects);
    // }
    private function cekStock($product_id){
        $stockIn = ProductStock::where([
            'product_id' => $product_id,
            'stockable_type' => Purchase::class,
        ])->isStock()->sum('qty');

        $stockOut = ProductStock::where('product_id', $product_id)
            ->where(function ($query) {
                $query->where('stockable_type', SaleDirect::class)
                    ->orWhere('stockable_type', Sale::class);
            })
            ->isStock()
            ->sum('qty');

        $stockAdjustment = ProductStock::where([
            'product_id' => $product_id,
            'stockable_type' => Adjustment::class
        ])
        ->isStock()
        ->get()->sum(function ($item) {
            $qty = abs($item->qty);
            return $item->status === 'minus' ? -$qty : $qty;
        });

        $availableStock = $stockIn - $stockOut + $stockAdjustment;

        return $availableStock;
    }

    public function index(Request $request)
    {
        $getTokenUser = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $getTokenUser)->first();
        $numberDesk = SaleDirectCustomerDesk::where('created_by', $userToken->user_id)->first();
        $cartDirect = SaleDirectCart::where('created_by', $userToken->user_id)->get();

        $array = [
            'customer_name' => isset($numberDesk->customer_name) ? $numberDesk->customer_name : null,
            'desk_name' => isset($numberDesk->desk_name) ? $numberDesk->desk_name : null,
            'email' => isset($numberDesk->email) ? $numberDesk->email : null,
            'items' => $cartDirect
        ];

        return response()->json($array);
    }

    public function plus(Request $request)
    {
        $getTokenUser = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $getTokenUser)->first();
        $id = $request->product_id;

        return $this->plusCart($id, $userToken->user_id);
    }

    public function minus(Request $request)
    {
        $getTokenUser = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $getTokenUser)->first();
        $id = $request->product_id;

        return $this->minusCart($id, $userToken->user_id);
    }

    public function updateQty(Request $request)
    {
        $getTokenUser = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $getTokenUser)->first();
        $id = $request->product_id;
        $qty = $request->qty;
        return $this->changeCart($id, $qty, $userToken->user_id);
    }

    public function deletes($id, Request $request)
    {
        $getTokenUser = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $getTokenUser)->first();

        return $this->deleteCart($id, $userToken->user_id);
    }

    public function store(Request $request)
    {
        if($this->cekStock($request->product_id) > 0){
            if ($returnProd = $this->storeCart($request->all(), $request->header('X-API-KEY'))) {
                return $returnProd;
            } else {
                return response()->json(['message' => 'Item gagal disimpan di keranjang', 'status' => false], 500);
            }
        } else {
            return response()->json(['message' => 'Stock kosong', 'status' => false], 500);
        }
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
        if ($this->updateSale($sale_api, $request->all(), $request->header('X-API-KEY'))) {
            return response()->json(['message' => 'Item berhasil diperbarui di keranjang', 'status' => true], 200);
        } else {
            return response()->json(['message' => 'Item gagal diperbarui di keranjang', 'status' => true], 200);
        }
    }

    public function deleteAllCart(Request $request)
    {
        $getTokenUser = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $getTokenUser)->first();

        SaleDirectCustomerDesk::where('created_by', $userToken->user_id)->delete();
        SaleDirectCart::where('created_by', $userToken->user_id)->delete();
        return response()->json(['message' => 'semua item berhasil dihapus', 'status' => true], 200);
    }

    public function destroy($id, Request $request)
    {
        $getTokenUser = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $getTokenUser)->first();

        return $this->deleteCart($id, $userToken->user_id);
    }
}
