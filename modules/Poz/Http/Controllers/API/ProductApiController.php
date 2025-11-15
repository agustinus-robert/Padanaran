<?php

namespace Modules\Poz\Http\Controllers\API;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\Product;
use Modules\Poz\Models\Supplier;
use Modules\Poz\Models\ProductStock;
use Modules\Poz\Models\Purchase;
use Modules\Poz\Models\SupplierSchedule;
use Modules\Poz\Models\PurchaseItems;
use Modules\Poz\Models\SaleDirect;
use Modules\Poz\Models\Adjustment;
use Modules\Poz\Models\Sale;
use Modules\Poz\Models\Brand;
use Modules\Poz\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Models\UserToken;
use Modules\Poz\Models\Casier;
use Modules\Poz\Models\SaleDirectCart;

class ProductApiController extends Controller
{
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
            })->isStock()->sum('qty');

        $stockAdjustment = ProductStock::where([
            'product_id' => $product_id,
            'stockable_type' => Adjustment::class
        ])->isStock()->get()->sum(function ($item) {
            $qty = abs($item->qty);
            return $item->status === 'minus' ? -$qty : $qty;
        });

        // $qtyInCart = SaleDirectCart::with(['saleItems' => function ($query) use ($product_id) {
        //     $query->where('product_id', $product_id);
        // }])
        //  //   ->where('created_by', $userToken->user_id)
        //     ->get()
        //     ->pluck('saleItems')
        //     ->flatten()
        //     ->sum('qty');

        $qtyInCart = SaleDirectCart::where(['created_by' => $userToken->user_id, 'product_id' => $product_id])->sum('qty');
        $availableStock = $stockIn - $stockOut + $stockAdjustment - $qtyInCart;

        return $availableStock;
    }

   public function productSupplier($supplier_id = null)
    {
        $query = Supplier::with('scheduleStock'); 

        if ($supplier_id) {
            $supplier = $query->find($supplier_id);
            if (!$supplier) {
                return []; 
            }

            return [
                $supplier->id => $supplier->products->pluck('name', 'id')->toArray()
            ];
        }

        return $query->get()
            ->mapWithKeys(function ($supplier) {
                return [
                    $supplier->id => $supplier->products->pluck('name', 'id')->toArray()
                ];
            })
            ->toArray();
    }


    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $categoryId = $request->get('category_id');
        $search = $request->get('search');

        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        $query = Product::with('outlets');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if (!empty($search)) {
            $query->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('code', 'LIKE', '%' . $search . '%');
        }

        $user = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $user)->first();
        $casier = Casier::where('user_id', $userToken->user_id)->first();

        $query->whereHas('outlets', function ($q) use ($casier) {
            $q->where('outlet_id', $casier->outlet_id);
        })->orderBy($sortBy, $sortOrder);

        $products = $query->paginate($perPage);

        $products->getCollection()->transform(function ($product) use ($request) {
            $product->price = (int) $product->price;  // Mengonversi harga menjadi integer
            $product->available_stock = (int) $this->cekStock($product->id, $request);
            return $product;
        });

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $location = 'file_product/' . uniqid();
        $user = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $user)->first();
        $casier = Casier::where('user_id', $userToken->user_id)->first();
        $cekBarcode = Product::where('code', $request->barcode)->count();
        $shift = $request->shift;

        if ($cekBarcode > 0) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Kode barcode sudah pernah didaftarkan'
                ],
                400
            );
        } else {

            $digits = '0123456789';
            $randomNumbers = substr(str_shuffle(str_repeat($digits, 10)), 0, 10);

            $data = [
                'type' => 1,
                'code' => $cekBarcode,
                'name' => $request->name,
                'price' => $request->price,
                'brand_id' => null,
                'created_by' => $userToken->user_id,
            ];

            if(empty($request->qty) || $request->qty == 0){
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Quantity Produk harap diisi'
                    ],
                    400
                );
            }

            if (empty($request->name)) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Nama Produk harap diisi'
                    ],
                    400
                );
            }

            if (empty($request->price) || $request->price == 0) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Harga Jual Produk harap diisi'
                    ],
                    400
                );
            }

            if (empty($request->wholesale)) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Harga Pembelian awal barang harap diisi'
                    ],
                    400
                );
            }

            $validShifts = ['morning', 'afternoon', 'evening'];
            $shift = strtolower($request->shift ?? '');

            if (empty($shift) || !in_array($shift, $validShifts)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Shift harap diisi dan harus salah satu dari: morning, afternoon, evening.'
                ], 400);
            }

            if (empty($request->supplier_id)) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Supplier harap diisi'
                    ],
                    400
                );
            }

            if (isset($request->document) && $request->document instanceof \Illuminate\Http\UploadedFile) {
                $request->document->storeAs($location, $request->document->getClientOriginalName(), 'public');
                $data['location'] = $location;
                $data['image_name'] = $request->document->getClientOriginalName();
            } else {
                $data['location'] = 'dummy/';
                $data['image_name'] = 'no-pictures.png';
            }

            $data['wholesale'] = $request->wholesale;

            if ($product = Product::create($data)) {
                $product->outlets()->syncWithoutDetaching($casier->outlet_id);

                $purchase = Purchase::create([
                    'reference' => 'REF' . '-' . rand(),
                    'supplier_id' => $request->supplier_id,
                    'is_pos' => 1,
                    'purchase_status' => 3,
                    'purchase_date' => now(),
                    'grand_total' => ($request->wholesale * $request->qty),
                    'discount' => 0,
                    'created_by' => $casier->user_id
                ]);

                $product->update(['wholesale' => $request->wholesale]);

                $purchase->outlets()->syncWithoutDetaching($casier->outlet_id);
                $purchaseItems = new PurchaseItems();
                $purchaseItems->purchase_id = $purchase->id;
                $purchaseItems->product_id = $product->id;
                $purchaseItems->qty = $request->qty;
                $purchaseItems->created_by = $casier->user_id;
                $purchaseItems->save();

                SupplierSchedule::create([
                    'supplier_id' => $request->supplier_id,
                    'product_id' => $product->id,
                    'day'        => null,
                    'time'       => $shift,
                ]);

                $productStock = ProductStock::create([
                    'product_id' => $product->id,
                    'supplier_id' => $request->supplier_id,
                    'stockable_id' => $purchase->id,
                    'stockable_type' => \Modules\Poz\Models\Purchase::class,
                    'status' => 'plus',
                    'grand_total' => ($request->wholesale * $request->qty),
                    'wholesale' => $request->wholesale,
                    'qty' => $request->qty,
                    'created_by' => $casier->user_id,
                ]);

                $productStock->outlets()->syncWithoutDetaching($casier->outlet_id);
                return response()->json(['message' => 'Data berhasil disimpan', 'status' => true], 200);
            } else {
                return response()->json(['message' => 'Data gagal disimpan', 'status' => false], 500);
            }
        }

        return response()->json(
            [
                'status' => false,
                'message' => 'Data gagal disimpan'
            ],
            409
        );
    }

    public function update($product_id, Request $request)
    {
        $location = 'file_product/' . uniqid();
        $user = $request->header('X-API-KEY');
        $userToken = UserToken::where('token', $user)->first();

        $prod = Product::where('id', $product_id);
        $cekProduct = $prod->first();
        $cekBarcode = $prod->count();
        $casier = Casier::where('user_id', $userToken->user_id)->first();

        $data = [
            'type' => 1,
            'code' => $cekProduct->code,
            'name' => $request->name,
            'price' => $request->price,
            'updated_by' => $userToken->user_id
        ];


        if (empty($request->name)) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Nama Produk harap diisi'
                ],
                400
            );
        }

        if (empty($request->price) || $request->price == 0) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Harga Jual Produk harap diisi'
                ],
                400
            );
        }

        if (isset($request->document) && $request->document instanceof \Illuminate\Http\UploadedFile) {
            $request->document->storeAs($location, $request->document->getClientOriginalName(), 'public');
            $data['location'] = $location;
            $data['image_name'] = $request->document->getClientOriginalName();
        }

        $product = Product::find($product_id);
        if ($product) {
            $product->update($data);

            // $productId = $product_id;
            // $supplierId = $request->supplier_id;
            // $outletId = $casier->outlet_id;
            // $newQty = $request->qty;

            // $availableStocks = ProductStock::where('product_id', $productId)
            //     ->where('supplier_id', $supplierId)
            //     ->where('stockable_type', Purchase::class)
            //     ->whereHas('outlets', function ($query) use ($outletId) {
            //         $query->where('outlet_id', $outletId);
            //     })
            //     ->orderBy('stockable_id', 'asc')
            //     ->get();

            // $totalStock = $availableStocks->sum('qty');
            // $difference = $newQty - $totalStock;

            // if((int) $newQty !== $totalStock){
            //     $dataStock = [
            //         'product_id' => $productId,
            //         'supplier_id' => $supplierId,
            //         'stockable_type' => \Modules\Poz\Models\Purchase::class,
            //         'stockable_id' => null,
            //         'created_by' => $casier->user_id
            //     ];

            //     if($newQty > $totalStock){
            //         $dataStock['status'] = 'plus';
            //     } else {
            //         $dataStock['status'] = 'minus';
            //     }

            //     $dataStock['qty'] = $newQty;

            //     ProductStock::create($dataStock)->outlets()->syncWithoutDetaching($outletId);
            // }
            //$availableStocks->outlets()->syncWithoutDetaching($casier->outlet_id);

            return response()->json(['message' => 'Data berhasil diperbarui'], 200);
        } else {
            return response()->json(['message' => 'Data berhasil disimpan'], 200);
        }


        return response()->json(
            [
                'status' => false,
                'message' => 'Data gagal disimpan'
            ],
            500
        );
    }

    public function destroy($product_id)
    {
        if (Product::where('id', $product_id)->delete()) {
            return response()->noContent(200);
        } else {
            return response()->noContent(404);
        }
    }

    public function show($product, Request $request)
    {
        $productShow = Product::with('purchaseItems')->findOrFail($product);
        $totalStock = $productShow->purchaseItems->sum('qty');

        $productShow->price = intval($productShow->price);
        $productShow->available_stock = (int) $this->cekStock($product, $request);

        return response()->json($productShow);
    }
}
