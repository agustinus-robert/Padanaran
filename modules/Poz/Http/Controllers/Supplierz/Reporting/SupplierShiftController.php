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
            dbuilder_table('outlet', 'Outlet'),
            dbuilder_table('shift', 'Pembelian'),
            dbuilder_table('tanggal', 'Tanggal'),
            dbuilder_table('stock', 'Stok Barang')
        ];

        $data['title'] = 'Laporan Shift Supplier';

        return view('poz::supplierz.reporting.product-supplier-reporting', $data);
    }

    public function getStockProducts(Request $request)
    {
        $outletId = $request->outlet;
        $shift = $request->shift;
        
        $user = auth()->id();
        $supplier = Supplier::where('user_id', $user)->first();

        $today = Carbon::today();

        $product = ProductStock::with([
            'outlets',
        ])
        ->where('supplier_id', $supplier->id)
        ->whereIn('stockable_type', [
            \Modules\Poz\Models\Adjustment::class,
            \Modules\Poz\Models\Purchase::class,
        ]);

        if ($request->report == 'now') {
            $product->whereDate('created_at', $today);
        } elseif ($request->report == 'thisweek'){
            $product->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($request->report == 'thismonth') {
            $product->whereMonth('created_at', now()->month);
        } elseif ($request->report == 'thisyear') {
            $product->whereYear('created_at', now()->year);
        } else {
           $product->whereDate('created_at', $today);
        }
        
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
                    return "<img src='https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg?20200913095930' width='50' height='50' />";
                }
            })
            ->addColumn('purchase', function ($row) {
                return ($row->product->name ?? '-');
            })
            ->addColumn('outlet', function ($row) {
                return $row->outlets->first()->name ?? '-';
            })
            ->addColumn('tanggal', function($row){
                return $row->created_at->translatedFormat('l, d M Y H:i');
            })
            ->addColumn('shift', function ($row) {
                return $row->shift;
            })
            ->addColumn('stock', function ($row) use ($today) {
                return $row->qty;
            })
            ->rawColumns(['image', 'purchase'])
            ->make(true);
    }
}
