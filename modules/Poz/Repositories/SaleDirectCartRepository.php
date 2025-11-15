<?php

namespace Modules\Poz\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\SaleDirectCart;
use Modules\Poz\Models\SaleDirect;
use Modules\Poz\Models\SaleDirectItems;
use Modules\Account\Models\UserToken;
use Modules\Poz\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


trait SaleDirectCartRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'reference',
        'customer_name',
        'desk_name',
        'sale_status',
        'subtotal',
        'discount',
        'total_payment',
        'grand_total',
        'note',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * Store newly created resource.
     */
    public function reduceProductStock($selected, $warehouseForm)
    {
        foreach ($selected as $item) {
            // Cari seluruh PurchaseItems yang terkait dengan product_id
            $purchaseItems = PurchaseItems::where(['product_id' => $item['id'], 'warehouse_id' => $warehouseForm])->get();

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

    public function storeCart(array $cart, $user)
    {
        DB::beginTransaction();

        $userToken = UserToken::where('token', $user)->first();

        try {
            $existingItem = SaleDirectCart::where('created_by', $userToken->user_id)
                ->where('product_id', $cart['product_id'])
                ->first();

            $requestedQty = $cart['qty'];
            $stock = $this->cekStock($cart['product_id']);

            if ($existingItem) {
                $newTotalQty = $existingItem->qty + $requestedQty;

                if ($newTotalQty > $stock) {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'Jumlah total barang melebihi stok tersedia',
                        'available_stock' => $stock,
                    ], 400);
                }

                $existingItem->qty += $cart['qty'];
                $existingItem->save();
            } else {
                $saleItems = new SaleDirectCart();

                $saleItems->product_id = $cart['product_id'];
                $saleItems->qty = $cart['qty'];
                $saleItems->product_name = productItem($cart['product_id'])->name;
                $saleItems->price = productItem($cart['product_id'])->price;
                $saleItems->location = productItem($cart['product_id'])->location;
                $saleItems->image_name = productItem($cart['product_id'])->image_name;
                $saleItems->created_by = $userToken->user_id;
                $saleItems->save();
            }


            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Item berhasil ditambahkan',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return response()->json([
                'status' => true,
                'message' => 'Item gagal ditambahkan',
            ], 500);
        }
    }

    public function plusCart($id, $token)
    {
        // $userToken = UserToken::where('token', $token)->first();

        $existingItem = SaleDirectCart::where('created_by', $token)
            ->where('product_id', $id)
            ->first();

        if ($existingItem) {
            $currentQty = $existingItem->qty;
            $stock = $this->cekStock($id);

            if ($currentQty + 1 > $stock) {
                return response()->json([
                    "status" => false,
                    "message" => "Stok tidak mencukupi untuk menambah produk.",
                    'available_stock' => $stock
                ], 400);
            }

            $existingItem->qty += 1;
            $existingItem->save();

            return response()->json([
                "status" => true,
                "message" => "Produk berhasil ditambahkan ke keranjang."
            ], 200);
        }

        return response()->json(["error" => "produk belum ditambahkan pada keranjang"], 404);
    }

    public function minusCart($id, $token)
    {

        $existingItem = SaleDirectCart::where('created_by', $token)
            ->where('product_id', $id)
            ->first();

        if ($existingItem) {
            if ($existingItem->qty <= 1) {
                return response()->json(['status' => false, 'error' => 'Jumlah produk tidak boleh kurang dari 1'], 400);
            }

            $existingItem->qty -= 1;  // Mengurangi 1 ke kuantitas
            $existingItem->save();
        } else {
            return response()->json(['status' => false, "error" => "produk belum ditambahkan pada keranjang"]);
        }

        return true;
    }

    public function deleteCart($id, $token)
    {
        $existingItem = SaleDirectCart::where('created_by', $token)
            ->where('product_id', $id)
            ->first();

        if ($existingItem) {
            $existingItem->delete();
            return response()->json(['status' => true, 'message' => 'item berhasil dihapus'], 200);
        } else {
            return response()->json(['status' => false, "error" => "produk belum ditambahkan pada keranjang"]);
        }

        return true;
    }

    public function changeCart($id, $qty, $token)
    {
        $cartItem = SaleDirectCart::where('created_by', $token)
            ->where('product_id', $id)
            ->first();

        if ($qty > $this->cekStock($id)) {
            return response()->json([
                'status' => false,
                'message' => 'Barang masuk tidak sesuai dengan total stok barang',
                'available_stock' => $this->cekStock($id)
            ], 400);
        }

        if ($cartItem) {
            if ($qty > 0) {
                $cartItem->qty = $qty;
                $cartItem->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Qty telah diubah',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Quantity harus lebih dari 0'
                ], 400);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Item tidak ditemukan di keranjang'
            ], 400);
        }
    }

    /**
     * Update the current resource.
     */
    public function updateSale($id, array $saleData, $user)
    {
        DB::beginTransaction();

        $userToken = UserToken::where('token', $user)->first();

        try {

            $data['discount'] = $saleData['discount'] ?? 0;
            $data['customer_name'] = $saleData['customer_name'];
            $data['desk_name'] = $saleData['desk_name'];
            $data['sale_status'] = $saleData['sale_status'];
            $data['subtotal'] = $saleData['subtotal'];
            $data['total_payment'] = $saleData['total_payment'];
            $data['grand_total'] = $saleData['grand_total'];
            $data['note'] = $saleData['note'];
            //jika bill at masih 0
            $data['bill_at'] = $saleData['bill_at'];
            $data['updated_by'] = $userToken->user_id;

            $sale = SaleDirect::find($id);

            SaleDirectItems::where('sale_id', $id)->delete();

            if ($sale->update(Arr::only($data, $this->keys))) {
                foreach ($saleData['items'] as $key => $value) {
                    $saleItems = new SaleDirectItems();
                    $saleItems->sale_id = $sale->id;
                    $saleItems->product_id = $value['product_id'];
                    $saleItems->product_name = productItem($value['product_id'])->name;
                    $saleItems->price = productItem($value['product_id'])->price;
                    $saleItems->product_name = $value['product_name'];
                    $saleItems->qty = $value['qty'];
                    $saleItems->save();
                }
            }

            // if($invoice['sale_status'] == 3){
            //     if (!$this->reduceProductStock($saleItem, $warehouseForm)) {
            //        DB::rollback();
            //        return 'stock';
            //     }
            // }

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
