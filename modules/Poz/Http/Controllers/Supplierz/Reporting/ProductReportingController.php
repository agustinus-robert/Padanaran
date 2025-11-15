<?php

namespace Modules\Poz\Http\Controllers\Supplierz\Reporting;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\Product;
use Modules\Poz\Models\Supplier;
use Illuminate\Http\Request;
use Modules\Poz\Models\Adjustment;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\Purchase;
use Modules\Poz\Models\ProductStock;
use Modules\Poz\Models\Sale;
use Modules\Poz\Models\SupplierSchedule;
use Carbon\Carbon;
use Modules\Poz\Models\SaleDirect;

class ProductReportingController extends Controller
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
            dbuilder_table('outlet', 'Outlet'),
            dbuilder_table('shift', 'Shift'),
            dbuilder_table('tanggal', 'Tanggal'),
            dbuilder_table('stock', 'Jumlah Pengembalian'),
            dbuilder_table('note', 'Catatan')
        ];

        $data['title'] = 'Laporan Shift Supplier';

        return view('poz::supplierz.reporting.product-reporting', $data);
    }

    public function getReportProducts(Request $request)
    {
        $outletId = $request->outlet;
        $shift = $request->shift;

        $user = auth()->id();
        $supplier = Supplier::where('user_id', $user)->first();

        $today = $request->date 
            ? \Carbon\Carbon::parse($request->date)->startOfDay() 
            : \Carbon\Carbon::today();

        // Ambil semua ProductStock Adjustment milik supplier
        $product = ProductStock::with(['outlets', 'product.productStockAdjustItems'])
            ->where('supplier_id', $supplier->id)
            ->whereIn('stockable_type', [
                \Modules\Poz\Models\Adjustment::class,
            ]);

        // Filter report
        if (!empty($request->report) && $request->report !== 'all') {
            if ($request->report === 'now') {
                $product->whereDate('created_at', $today);
            } elseif ($request->report === 'yesterday') {
                $yesterday = \Carbon\Carbon::yesterday();
                $product->whereDate('created_at', $yesterday);
            } elseif ($request->report === 'thisweek') {
                $product->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($request->report === 'thismonth') {
                $product->whereMonth('created_at', now()->month);
            } elseif ($request->report === 'thisyear') {
                $product->whereYear('created_at', now()->year);
            }
        } else {
            // default hari ini
            $product->whereDate('created_at', $today);
        }

        // Filter shift
        if (in_array($shift, ['morning', 'afternoon', 'evening'])) {
            $product->where('shift', $shift);
        }

        return Table::of($product)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                if (!empty($row->product?->location) && !empty($row->product?->image_name)) {
                    $image = $row->product->location . '/' . $row->product->image_name;
                    return "<img width='50' height='50' src='" . asset('uploads/' . $image) . "' />";
                } else {
                    return "<img src='https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg' width='50' height='50' />";
                }
            })
            ->addColumn('purchase', fn($row) => $row->product->name ?? '-')
            ->addColumn('outlet', fn($row) => $row->outlets->first()->name ?? '-')
            ->addColumn('tanggal', fn($row) => $row->created_at?->translatedFormat('l, d M Y H:i') ?? '-')
            ->addColumn('shift', fn($row) => $row->shift ?? '-')
            ->addColumn('stock', function($row) use ($today, $request) {
                $items = $row->product?->productStockAdjustItems ?? collect();

                // Filter report tambahan
                if (!empty($request->report) && $request->report !== 'all') {
                    if ($request->report === 'now') {
                        $items = $items->filter(fn($item) => $item->created_at && \Carbon\Carbon::parse($item->created_at)->isSameDay($today));
                    } elseif ($request->report === 'yesterday') {
                        $yesterday = \Carbon\Carbon::yesterday();
                        $items = $items->filter(fn($item) => $item->created_at && \Carbon\Carbon::parse($item->created_at)->isSameDay($yesterday));
                    } elseif ($request->report === 'thismonth') {
                        $items = $items->filter(fn($item) => $item->created_at &&
                            \Carbon\Carbon::parse($item->created_at)->month === now()->month &&
                            \Carbon\Carbon::parse($item->created_at)->year === now()->year
                        );
                    } elseif ($request->report === 'thisyear') {
                        $items = $items->filter(fn($item) => $item->created_at &&
                            \Carbon\Carbon::parse($item->created_at)->year === now()->year
                        );
                    }
                } else {
                    $items = $items->filter(fn($item) => $item->created_at && \Carbon\Carbon::parse($item->created_at)->isSameDay($today));
                }

                // Hanya ambil yang status minus
                $items = $items->filter(fn($item) => $item->status === 'minus');

                return $items->sum('qty'); // total qty minus
            })
            ->addColumn('note', fn($row) => $row->stockable?->note ?? '-')
            ->rawColumns(['image', 'purchase'])
            ->make(true);
    }


}
