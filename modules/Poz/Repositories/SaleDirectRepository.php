<?php

namespace Modules\Poz\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\PurchaseItems;
use Modules\Poz\Models\SaleDirect;
use Modules\Poz\Models\SaleDirectItems;
use Modules\Poz\Models\SaleDirectCart;
use Modules\Poz\Models\SaleDirectCustomerDesk;
use Modules\Account\Models\UserToken;
use Modules\Poz\Models\CashRegister;
use Modules\Poz\Models\CashHistoryRegister;
use Modules\Poz\Models\ProductStock;
use Modules\Poz\Models\Product;
use Modules\Poz\Models\Supplier;
use Modules\Poz\Models\SupplierSchedule;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait SaleDirectRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'reference',
        'bill_at',
        'customer_name',
        'desk_name',
        'email',
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

    public function storeSale(array $invoice, $user)
    {
        DB::beginTransaction();

        $userToken = UserToken::where('token', $user)->first();

        $saleDeskCustomer = SaleDirectCustomerDesk::where('created_by', $userToken->user_id)->first();
        $items = SaleDirectCart::where('created_by', $userToken->user_id)->get();

        // if (empty($saleDeskCustomer)) {

        //     return response()->json([
        //             'status' => false,
        //             'message' => 'Customer dan Meja harus diisi'
        //     ], 404); // Status code 404 untuk "Not Found"
        // }

        if ($items->count() == 0) {
            return response()->json([
                'status' => false,
                'message' => 'Cart masih kosong'
            ], 500);
        }

        $cashRegister = CashRegister::where('casier_id', $userToken->user_id)->first();
        if (empty($cashRegister)) {
            return response()->json([
                'status' => false,
                'message' => 'Cash register belum ada'
            ], 500);
        }

        $saleDirectCart = SaleDirectCart::where('created_by', $userToken->user_id)->get();

        try {
            $data['reference'] = 'REF' . '-' . rand();
            if (!isset($saleDeskCustomer['customer_name'])) {
                $data['customer_name'] = null;
            } else {
                $data['customer_name'] = $saleDeskCustomer['customer_name'];
            }

            if (!isset($data['desk_name'])) {
                $data['desk_name'] = null;
            } else {
                $data['desk_name'] = $saleDeskCustomer['desk_name'];
            }

            $data['sale_status'] = $invoice['sale_status'];
            $saleDirectCarts = SaleDirectCart::where('created_by', $userToken->user_id)->get();


            $hitungSubtotal = 0;
            foreach ($saleDirectCarts as $key => $dt_val) {
                $hitungSubtotal += $dt_val->qty * $dt_val->price;
            }

            $data['subtotal'] = $hitungSubtotal;
            $pajak11 = $hitungSubtotal * 0.11;
            $data['discount'] = $invoice['discount'] ?? 0;

            if ($invoice['bill_at'] !== null) {
                $data['total_payment'] = $invoice['total_payment'];
            } else {
                $data['total_payment'] = 0;
            }

            $data['grand_total'] = ($hitungSubtotal + 0) - $invoice['discount'];

            if ($invoice['bill_at'] !== null) {
                if ((float) $invoice['total_payment'] > (float) $data['grand_total']) {
                    $hitungBalanced = (float) $invoice['total_payment'] - (float) $data['grand_total'];
                    $cashRegister = CashRegister::where('casier_id', $userToken->user_id)->first();

                    //ambil sisa kembalian
                    if($cashRegister->money == 0){
                          return response()->json([
                            'status' => false,
                            'cash' => $cashRegister->money,
                            'balaced' => $hitungBalanced,
                            'message' => 'Uang cash kurang untuk kembalian, silahkan top Up dulu, sebelum melanjutkan transaksi'
                        ], 500);
                    } else if($hitungBalanced >= $cashRegister->money){
                        $remain = $hitungBalanced - $cashRegister->money;
                        $cashRegister->money = 0;
                        $cashRegister->save();

                        //beri sisa kembalian
                        $cashRegister->logCash()->create([
                            'status' => 'minus', 
                            'money' => $remain,
                        ]);
                    } else if ($cashRegister->money >= $hitungBalanced) {
                        $cashRegister->money -= $hitungBalanced;
                        $cashRegister->save();

                        $cashRegister->logCash()->create([
                            'status' => 'minus', 
                            'money' => $hitungBalanced,
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'cash' => $cashRegister->money,
                            'balaced' => $hitungBalanced,
                            'message' => 'Uang cash kurang untuk kembalian, silahkan top Up dulu, sebelum melanjutkan transaksi'
                        ], 500);
                    }
                } else if ((float) $invoice['total_payment'] < (float) $data['grand_total']) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Maaf uang anda kurang'
                    ], 500);
                }
            }



            $data['note'] = $invoice['note'];
            $data['bill_at'] = $invoice['bill_at'];
            $data['created_by'] = $userToken->user_id;
            $saleDirectTemp = SaleDirectCart::where('created_by', $data['created_by'])->get();

            $overStockedProducts = [];
            foreach ($saleDirectTemp as $value) {
                $productId = $value['product_id'];
                $qtyRequested = $value['qty']; // asumsi qty disimpan di field ini

                $stockData = ProductStock::where('product_id', $productId)->sum('qty');

                $stock = $stockData ? $stockData : 0;

                if ($qtyRequested > $stock) {
                    $overStockedProducts[] = [
                        'name'  =>  Product::find($productId)->name,
                        'stock_on_cart' => $value['qty'],
                        'stock_available' => $stockData
                    ];
                }
            }

            if(count($overStockedProducts) > 0){
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Maaf ada barang yang melebihi stock',
                    'detail_cart' => $overStockedProducts
                ], 500);
            }

            $sale = new SaleDirect(Arr::only($data, $this->keys));

            if ($sale->save()) {
                $saleDirectCarts = SaleDirectCart::where('created_by', $sale->created_by)->get();

                foreach ($saleDirectCarts as $key => $value) {

                    $saleItems = new SaleDirectItems();
                    $saleItems->sale_id = $sale->id;

                    //jika bill_at
                    $saleItems->product_id = $value['product_id'];
                    $saleItems->qty = $value['qty'];
                    $saleItems->product_name = productItem($value['product_id'])->name;
                    $saleItems->price = productItem($value['product_id'])->price;
                    $saleItems->location = productItem($value['product_id'])->location;
                    $saleItems->image_name = productItem($value['product_id'])->image_name;

                    $saleItems->save();

                    if ($saleItems->save() && $data['sale_status'] == 3) {
                        $supplier = SupplierSchedule::with('supplier')->where('product_id', $value['product_id'])->first();
                        //$supplier = PurchaseItems::with('purchase')->where('product_id', $value['product_id'])->first();

                        ProductStock::create([
                            'product_id' => $value['product_id'],
                            'supplier_id' => $supplier->supplier->id,
                            'stockable_type' => \Modules\Poz\Models\SaleDirect::class,
                            'stockable_item' => $sale->id,
                            'grand_total' => (productItem($value['product_id'])->price * $value->qty),
                            'status' => 'minus',
                            'qty' => $value['qty'],
                            'wholesale' => productItem($value['product_id'])->wholesale,
                            'pricesale' => productItem($value['product_id'])->price,
                            'created_by' => $userToken->user_id
                        ]);
                    }
                }

                SaleDirectCart::where('created_by', $sale->created_by)->delete();
                SaleDirectCustomerDesk::where('created_by', $sale->created_by)->delete();
            }

            // if($invoice['sale_status'] == 3){
            //     if (!$this->reduceProductStock($saleItem, $warehouseForm)) {
            //        DB::rollback();
            //        return false;
            //     }
            // }

            DB::commit();
            return response()->json(['status' => true, 'message' => $data['reference'], 200]);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return response()->json([
                'status' => false,
                'message' => 'data gagal masuk'
            ], 500);
        }
    }

    public function addDirectItems(array $invoice, $id, $user)
    {
        DB::beginTransaction();

        $userToken = UserToken::where('token', $user)->first();

        try {
            $existingItem = SaleDirectItems::where(['sale_id' => $id, 'product_id' => $invoice['product_id']])->first();

            if ($existingItem) {
                $existingItem->qty += $invoice['qty'];
                $existingItem->save();
            } else {
                $saleItems = new SaleDirectItems();

                $saleItems->sale_id = $id;
                $saleItems->product_id = $invoice['product_id'];
                $saleItems->qty = $invoice['qty'];
                $saleItems->product_name = productItem($invoice['product_id'])->name;
                $saleItems->price = productItem($invoice['product_id'])->price;
                $saleItems->location = productItem($invoice['product_id'])->location;
                $saleItems->image_name = productItem($invoice['product_id'])->image_name;
                $saleItems->save();
            }


            DB::commit();
            return response()->json(['status' => true, 'message' => 'Item Transaksi Berhasil Disimpan']);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    public function deskCustDirect(array $invoice, $id, $user)
    {
        $userToken = UserToken::where('token', $user)->first();

        $directs = SaleDirect::where('id', $id)->first();

        $directs->customer_name = $invoice['customer_name'];
        $directs->desk_name = $invoice['desk_name'];
        $directs->email = $invoice['email'];

        if ($directs->save()) {
            return response()->json(['status' => true, 'message' => 'Customer, Meja, dan Email berhasil disimpan']);
        }

        return response()->json(['status' => false]);
    }

    public function deleteDirectItems(array $invoice, $id, $user)
    {
        DB::beginTransaction();

        $userToken = UserToken::where('token', $user)->first();

        try {
            $cek = SaleDirectItems::where(['sale_id' => $id, 'product_id' => $invoice['product_id']])->first();

            if (!$cek) {
                return response()->json(['status' => true, 'message' => 'Item pada transaksi ini tidak ditemukan']);
            }

            SaleDirectItems::where(['sale_id' => $id, 'product_id' => $invoice['product_id']])->delete();

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Item Transaksi Berhasil Dihapus']);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    public function updateQtyDirectItems(array $invoice, $id, $user)
    {
        $cartItem = SaleDirectItems::where('sale_id', $id)
            ->where('product_id', $invoice['product_id'])
            ->first();

        if ($cartItem) {
            if ($invoice['qty'] > 0) {
                $cartItem->qty = $invoice['qty'];
                $cartItem->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Qty Item pada transaksi telah diubah',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Quantity harus lebih dari 0'
                ], 400);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Item tidak ditemukan di transaksi'
            ], 500);
        }
    }

    /**
     * Update the current resource.
     */
    public function updateSale($id, array $invoice, $user)
    {
        DB::beginTransaction();

        $userToken = UserToken::where('token', $user)->first();

        $saleDeskCustomer = SaleDirectCustomerDesk::where('created_by', $userToken->user_id)->first();
        $items = SaleDirectCart::where('created_by', $userToken->user_id)->get();

        // if($items->count() == 0){
        //     return response()->json([
        //             'status' => false,
        //             'message' => 'Cart masih kosong'
        //     ], 404);
        // }

        $cashRegister = CashRegister::where('casier_id', $userToken->user_id)->first();
        if (empty($cashRegister)) {
            return response()->json([
                'status' => false,
                'message' => 'Cash register belum ada'
            ], 500);
        }

        $saleDirectCart = SaleDirectCart::where('created_by', $userToken->user_id)->get();

        try {

            if (!isset($saleDeskCustomer['customer_name'])) {
                $data['customer_name'] = null;
            } else {
                $data['customer_name'] = $saleDeskCustomer['customer_name'];
            }

            if (!isset($data['desk_name'])) {
                $data['desk_name'] = null;
            } else {
                $data['desk_name'] = $saleDeskCustomer['desk_name'];
            }

            $data['sale_status'] = $invoice['sale_status'];
            $saleDirectCarts = SaleDirectItems::where('sale_id', $id)->get();

            $getSale = SaleDirect::find($id);

            $hitungSubtotal = 0;
            foreach ($saleDirectCarts as $key => $dt_val) {
                $hitungSubtotal += $dt_val->qty * $dt_val->price;
            }

            $data['subtotal'] = $hitungSubtotal;
            $pajak11 = $hitungSubtotal * 0.11;
            $disc = 0;
            if (!empty($invoice['discount'])) {
                $data['discount'] = $invoice['discount'];
                $disc = $invoice['discount'];
            } else {
                $data['discount'] = $getSale->discount;
                $disc = $getSale->discount;
            }

            if ($invoice['bill_at'] !== null) {
                $data['total_payment'] = (float) $invoice['total_payment'];
            } else {
                $data['total_payment'] = 0;
            }

            $data['grand_total'] = ($hitungSubtotal + 0) - $disc;

            if ($invoice['bill_at'] !== null) {
                if ((float) $invoice['total_payment'] > (float) $data['grand_total']) {
                    $hitungBalanced = (float) $invoice['total_payment'] - (float) $data['grand_total'];
                    $cashRegister = CashRegister::where('casier_id', $userToken->user_id)->first();

                    if ($cashRegister->money >= $hitungBalanced) {
                        $cashRegister->money -= $hitungBalanced;
                        $cashRegister->save();
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'Uang cash kurang untuk kembalian, silahkan top Up dulu, sebelum melanjutkan transaksi'
                        ], 400);
                    }
                } else if ((float) $invoice['total_payment'] < (float) $data['grand_total']) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Maaf uang anda kurang'
                    ], 400);
                }
            }


            $data['note'] = $invoice['note'];
            $data['bill_at'] = date('Y-m-d H:i:s', strtotime($invoice['bill_at']));
            $data['updated_by'] = $userToken->user_id;

            // $saleDirectTemp = SaleDirectCart::where('created_by', $userToken->user_id)->get();

            // $overStockedProducts = [];
            // foreach ($saleDirectTemp as $value) {
            //     $productId = $value['product_id'];
            //     $qtyRequested = $value['qty']; // asumsi qty disimpan di field ini

            //     $stockData = ProductStock::where('product_id', $productId)->sum('qty');

            //     $stock = $stockData ? $stockData : 0;

            //     if ($qtyRequested > $stock) {
            //         $overStockedProducts[] = [
            //             'name'  =>  Product::find($productId)->name,
            //             'stock_on_cart' => $value['qty'],
            //             'stock_available' => $stockData
            //         ];
            //     }
            // }

            // if (count($overStockedProducts) > 0) {
            //     DB::rollBack();
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'Maaf ada barang yang melebihi stock',
            //         'detail_cart' => $overStockedProducts
            //     ], 500);
            // }

            // $sale = SaleDirect::with('saleItems')->find($id);
            // SaleDirectItems::where('sale_id', $id)->delete();

            // if ($sale->update(Arr::only($data, $this->keys))) {
            //     foreach($saleData['items'] as $key => $value){
            //         $saleItems = new SaleDirectItems();
            //         $saleItems->sale_id = $sale->id;
            //         $saleItems->product_id = $value['product_id'];
            //         $saleItems->product_name = productItem($value['product_id'])->name;
            //         $saleItems->price = productItem($value['product_id'])->price;
            //         $saleItems->product_name = $value['product_name'];
            //         $saleItems->qty = $value['qty'];
            //         $saleItems->save();
            //     }
            // }

            $sale = SaleDirect::find($id);

            $sales = SaleDirect::with('saleItems')
                ->where('created_by', $sale->created_by)
                ->where('id', $id)
                ->get();

            if ($sale->update(Arr::only($data, $this->keys))) {
                $saleDirectCarts = SaleDirectCart::where('created_by', $sale->created_by)->get();

                if($data['sale_status'] == 3){
                    foreach ($sales as $value) {
                        foreach ($value->saleItems as $dtsale) {
                            // $supplier = PurchaseItems::with('purchase')->where('product_id', $dtsale->product_id)->first();
                            $supplier = SupplierSchedule::with('supplier')->where('product_id', $dtsale->product_id)->first();

                            ProductStock::create([
                                'product_id' => $dtsale->product_id,
                                'supplier_id' => $supplier->supplier->id,
                                'stockable_type' => \Modules\Poz\Models\SaleDirect::class,
                                'stockable_id' => $id,
                                'status' => 'minus',
                                'qty' => $dtsale->qty,
                                'wholesale' => productItem($dtsale->product_id)->wholesale,
                                'pricesale' => productItem($dtsale->product_id)->price,
                                'created_by' => $userToken->user_id
                            ]);
                        }
                    }
                }
            }

            // if($invoice['sale_status'] == 3){
            //     if (!$this->reduceProductStock($saleItem, $warehouseForm)) {
            //        DB::rollback();
            //        return 'stock';
            //     }
            // }

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => $sale->reference
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e->getMessage());
          //  return response()->json(['status' => true, 'message' => 'Data Gagal Diperbarui']);
            return response()->json([
                'status' => false,
                'message' => 'Data Gagal Diperbarui'
            ], 500);
        }
    }

    /**
     * Remove the current resource.
     */
    public function destroySale($id)
    {
        if (SaleDirect::where('id', $id)->delete()) {
            SaleDirectItems::where(['sale_id' => $id])->delete();
            return response()->json(['status' => true, 'message' => 'Data Transaksi berhasil dihapus']);
        }
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
