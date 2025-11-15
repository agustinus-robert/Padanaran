<?php

namespace Modules\Poz\Http\Controllers\Reporting;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\Product;
use Illuminate\Http\Request;
use Modules\Poz\Models\Adjustment;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\Purchase;
use Modules\Poz\Models\Sale;
use Modules\Poz\Models\SupplierSchedule;
use Carbon\Carbon;
use Modules\Poz\Models\SaleDirect;

class SupplierShiftController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function index()
    {
        $data = [];
        // dbuilder_table untuk membuat generate table pada kolom header dan pemanggilan kolom database
        $data['column'] = [
            //DT_RowIndex usahakan false karena tidak ada secara fisik pada database
            dbuilder_table('purchase', 'Nama Produk'),
            dbuilder_table('supplier', 'Supplier'),
            dbuilder_table('shift', 'Pembelian'),
            dbuilder_table('stock', 'Stok Barang')
        ];

        $data['title'] = 'Laporan Shift Supplier';

        return view('poz::reporting.supplier-shift-reporting', $data);
    }

   public function getStockProducts(Request $request)
    {
        $outletId = $request->outlet;
        $shift = $request->shift;

        $today = $request->date 
            ? \Carbon\Carbon::parse($request->date)->startOfDay() 
            : \Carbon\Carbon::today();

        $product = SupplierSchedule::with([
            'supplier.outlets',
            'product.productStockAdjustItems'
        ])
        // filter outlet
        ->whereHas('supplier.outlets', function ($query) use ($outletId) {
            $query->where('outlet_id', $outletId);
        });

        // filter shift (jika dikirim)
        if (in_array($shift, ['morning', 'afternoon', 'evening'])) {
            $product->where('time', $shift);
        }

        return Table::of($product)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                if (!empty($row->product?->location) && !empty($row->product?->image_name)) {
                    $image = $row->product->location . '/' . $row->product->image_name;
                    return "<img width='50' height='50' src='" . asset('uploads/' . $image) . "' />";
                } else {
                    return "<img src='https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg?20200913095930' width='50' height='50' />";
                }
            })
            ->addColumn('purchase', function ($row) {
                return ($row->product->name ?? '-');
            })
            ->addColumn('supplier', function ($row) {
                return $row->supplier->name ?? '-';
            })
            ->addColumn('shift', function ($row) {
                return \Modules\Core\Enums\SupplierWorkEnum::fromKey($row->time)?->labelIndo() ?? '-';
            })
            ->addColumn('stock', function ($row) use ($today, $request) {
                if (!$row->product || !$row->product->productStockAdjustItems) {
                    return 0;
                }

                $items = $row->product->productStockAdjustItems()->isStock()
                ->get();

                // filter report tambahan
                if (!empty($request->report) && $request->report !== 'all') {
                    if ($request->report === 'now') {
                        $items = $items->filter(function ($item) use ($today) {
                            return $item->created_at &&
                                \Carbon\Carbon::parse($item->created_at)->isSameDay($today);
                        });
                    } elseif ($request->report === 'yesterday') {
                        $yesterday = \Carbon\Carbon::yesterday();
                        $items = $items->filter(function ($item) use ($yesterday) {
                            return $item->created_at &&
                                \Carbon\Carbon::parse($item->created_at)->isSameDay($yesterday);
                        });
                    }  elseif ($request->report === 'thismonth') {
                        $items = $items->filter(function ($item) {
                            return $item->created_at &&
                                \Carbon\Carbon::parse($item->created_at)->month === now()->month &&
                                \Carbon\Carbon::parse($item->created_at)->year === now()->year;
                        });
                    } elseif ($request->report === 'thisyear') {
                        $items = $items->filter(function ($item) {
                            return $item->created_at &&
                                \Carbon\Carbon::parse($item->created_at)->year === now()->year;
                        });
                    }
                } else {
                    // default: filter hari ini
                    $items = $items->filter(function ($item) use ($today) {
                        return $item->created_at &&
                            \Carbon\Carbon::parse($item->created_at)->isSameDay($today);
                    });
                }

                // exclude tipe tertentu
                $items = $items->filter(function ($item) {
                    return !in_array($item->stockable_type, [
                        \Modules\Poz\Models\Sale::class,
                        \Modules\Poz\Models\SaleDirect::class,
                    ]);
                });

                // hitung stok
                return $items->reduce(function ($carry, $item) {
                    if ($item->status === 'plus') {
                        return $carry + $item->qty;
                    } elseif ($item->status === 'minus') {
                        return $carry - $item->qty;
                    }
                    return $carry;
                }, 0);
            })
            ->rawColumns(['image', 'purchase'])
            ->make(true);
    }

}
