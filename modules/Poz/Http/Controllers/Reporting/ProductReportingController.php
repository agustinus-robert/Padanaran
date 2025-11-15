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
            dbuilder_table('image', 'Gambar', false, false, 'w10'),
            dbuilder_table('name', 'Nama Produk'),
            dbuilder_table('purchase', 'Pembelian', false, true),
            dbuilder_table('selling', 'Penjualan'),
            dbuilder_table('profit', 'Laba/Rugi'),
            dbuilder_table('stock', 'Stok Barang')
        ];

        $data['title'] = 'Laporan Produk';

        return view('poz::reporting.product-reporting', $data);
    }

    public function productReportTable(Request $request)
{
    $outletId = $request->outlet;

    $product = Product::with(['outlets', 'purchaseItems', 'saleItems', 'productStockAdjustItems' => function ($q) use ($request) {
        if (!empty($request->report) && $request->report !== 'all') {
            if ($request->report === 'now') {
                $q->whereDate('created_at', now()->toDateString());
            } elseif ($request->report === 'yesterday') {
                $q->whereDate('created_at', now()->subDay()->toDateString());
            } elseif ($request->report === 'thismonth') {
                $q->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
            } elseif ($request->report === 'thisyear') {
                $q->whereYear('created_at', now()->year);
            }
        }
    }])
    ->whereNull('deleted_at')
    ->whereHas('outlets', function ($query) use ($outletId) {
        $query->where('outlet_id', $outletId);
    });

    // Default report adalah hari ini
    if (empty($request->report) || $request->report === 'all') {
        $request->merge(['report' => 'now']);
    }

    if (!empty($search = $request->search)) {
        $product->where(function ($query) use ($search) {
            $query->where('name', 'ILIKE', "%{$search}%");
        });
    }

    if (!empty($order = $request->filter)) {
        if ($order === 'new') {
            $product->orderBy('id', 'desc');
        } elseif ($order === 'old') {
            $product->orderBy('id', 'asc');
        }
    }

    return Table::of($product)
        ->addIndexColumn()
        ->addColumn('image', function ($row) {
            if (!empty($row->location) && !empty($row->image_name)) {
                $image = $row->location . '/' . $row->image_name;
                return "<img width='50' height='50' src='" . asset('uploads/' . $image) . "' />";
            } else {
                return "<img src='https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg?20200913095930' width='50' height='50' />";
            }
        })
        ->addColumn('purchase', function($row) {

            $adjustmentQty = $row->productStockAdjustItems()->isStock()
                ->get()
                ->filter(fn($item) => in_array($item->stockable_type, [Purchase::class, Adjustment::class]))
                ->sum(fn($adj) => ($adj->status === 'plus' ? 1 : ($adj->status === 'minus' ? -1 : 0)) * $adj->qty);

            $adjustmentTotal = $row->productStockAdjustItems
                ->filter(fn($adj) => in_array($adj->stockable_type, [Purchase::class, Adjustment::class]))
                ->sum(fn($adj) => ($adj->status === 'plus' ? 1 : ($adj->status === 'minus' ? -1 : 0)) * $adj->wholesale * $adj->qty);

            return "({$adjustmentQty}) " . number_format($adjustmentTotal, 0, ',', '.');
        })
        ->addColumn('selling', function($row) {
            $stockSellQty = $row->productStockAdjustItems()->isStock()
                ->get()
                ->filter(fn($item) => in_array($item->stockable_type, [SaleDirect::class, Sale::class]))
                ->sum(fn($adj) => $adj->qty);

            $stockSellTotal = $row->productStockAdjustItems()->isStock()
                ->get()
                ->filter(fn($item) => in_array($item->stockable_type, [SaleDirect::class, Sale::class]))
                ->sum(fn($adj) => $adj->qty * $adj->pricesale);

            return '(' . $stockSellQty . ') ' . number_format($stockSellTotal, 0, ',', '.');
        })
        ->addColumn('profit', function($row) {
            $stockSellTotal = $row->productStockAdjustItems()->isStock()
                ->get()
                ->filter(fn($item) => in_array($item->stockable_type, [SaleDirect::class, Sale::class]))
                ->sum(fn($adj) => $adj->qty * $adj->pricesale);

            $stockPurchaseTotal = $row->productStockAdjustItems()->isStock()
                ->get()
                ->filter(fn($adj) => in_array($adj->stockable_type, [Purchase::class, Adjustment::class]))
                ->sum(fn($adj) => ($adj->status === 'plus' ? 1 : ($adj->status === 'minus' ? -1 : 0)) * $adj->wholesale * $adj->qty);

            $total = $stockSellTotal - $stockPurchaseTotal;
            return number_format($total, 0, ',', '.');
        })
        ->addColumn('stock', function($row) {
            $adjustmentQty = $row->productStockAdjustItems()->isStock()
                ->get()
                ->filter(fn($item) => in_array($item->stockable_type, [Purchase::class, Adjustment::class]))
                ->sum(fn($adj) => ($adj->status === 'plus' ? 1 : ($adj->status === 'minus' ? -1 : 0)) * $adj->qty);

            $stockSellQty = $row->productStockAdjustItems()->isStock()
                ->get()
                ->filter(fn($item) => in_array($item->stockable_type, [SaleDirect::class, Sale::class]))
                ->sum(fn($adj) => $adj->qty);

            return $adjustmentQty - $stockSellQty;
        })
        ->rawColumns(['image', 'action'])
        ->make(true);
}

}
