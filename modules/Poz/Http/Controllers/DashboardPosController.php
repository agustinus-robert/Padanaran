<?php

namespace Modules\Poz\Http\Controllers;

use Modules\Poz\Models\ProductStock;
use Modules\Poz\Models\Purchase;
use Modules\Poz\Models\Sale;
use Illuminate\Http\Request;
use Modules\Poz\Models\SaleDirect;
use Modules\Poz\Models\Adjustment;
use Modules\Reference\Http\Controllers\Controller;

class DashboardPosController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function index(Request $request)
    {
        $outletId = $request->outlet;
        $productStocks = ProductStock::with('product')->get();

        $filteredStocksPurchase = $productStocks->filter(function ($stock) {
            return in_array($stock->modelable_type, [Purchase::class, Adjustment::class]);
        });

        $filteredStocksSell = $productStocks->filter(function ($stock) {
            return in_array($stock->modelable_type, [Sale::class, SaleDirect::class]);
        });

        $filteredThisMonthPurchase = $filteredStocksPurchase->filter(function ($item) {
            return $item->created_at->month === now()->month &&
                $item->created_at->year === now()->year;
        });

        $filteredThisMonthSell = $filteredStocksSell->filter(function ($item) {
            return $item->created_at->month === now()->month &&
                $item->created_at->year === now()->year;
        });

        $summaryBuyMonth = [
            'qty' => $filteredThisMonthPurchase->sum(function ($item) {
                $sign = $item->status === 'plus' ? 1 : ($item->status === 'minus' ? -1 : 0);
                return $sign * $item->qty;
            }),
            'total' => $filteredThisMonthPurchase->sum(function ($item) {
                $sign = $item->status === 'plus' ? 1 : ($item->status === 'minus' ? -1 : 0);
                return $sign * $item->qty * $item->wholesale;
            }),
        ];

        $summarySellMonth = [
            'qty' => $filteredThisMonthSell->sum('qty'),
            'total' => $filteredThisMonthSell->sum(function ($item) {
                return $item->qty * $item->pricesale;
            }),
        ];

        $productStocks = ProductStock::with('product')
            ->whereNull('deleted_at')->get();

        $sellStocks = $productStocks->filter(function ($stock) {
            return in_array($stock->modelable_type, [Sale::class, SaleDirect::class]);
        });

        $topProducts = $sellStocks
            ->groupBy('product_id')
            ->map(function ($items) {

                return [
                    'product_id' => $items->first()->product_id,
                    'product' => $items->first()->product,
                    'qty_sold' => $items->sum('qty'),
                    'total_sales' => $items->sum(function ($item) {
                        return $item->qty * $item->pricesale;
                    }),
                    'total_wholesale' => $items->sum(function ($item) {
                        return $item->qty * $item->wholesale;
                    })
                ];
            })
            ->sortByDesc('qty_sold')
            ->values()
            ->take(5);

        // $outletId = $request->outlet;

        $labels = $topProducts->pluck('product.name')->toArray();
        $dataSales = $topProducts->pluck('qty_sold')->toArray();
        return view('poz::dashboard', compact('summaryBuyMonth', 'summarySellMonth', 'topProducts', 'labels', 'dataSales'));
    }
}
