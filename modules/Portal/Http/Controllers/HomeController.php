<?php

namespace Modules\Portal\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Account\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\ProductStock;
use Modules\Poz\Models\Purchase;
use Modules\Poz\Models\Sale;
use Modules\Poz\Models\SaleDirect;
use Modules\Poz\Models\Adjustment;
use Modules\Portal\Repositories\ScheduleRepository;
use Modules\Portal\Repositories\ServiceRepository;

class HomeController extends Controller
{
    use ScheduleRepository, ServiceRepository;
    /**
     * Display home view
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $productStocks = ProductStock::with('product')->get();

        $filteredStocksPurchase = $productStocks->filter(function ($stock) {
            return in_array($stock->stockable_type, [Purchase::class, Adjustment::class]);
        });

        $filteredStocksSell = $productStocks->filter(function ($stock) {
            return in_array($stock->stockable_type, [Sale::class, SaleDirect::class]);
        });

        $summaryBuy = [
            'qty' => $filteredStocksPurchase->sum(function ($item) {
                $sign = $item->status === 'plus' ? 1 : ($item->status === 'minus' ? -1 : 0);
                return $sign * $item->qty;
            }),
            'total' => $filteredStocksPurchase->sum(function ($item) {
                $sign = $item->status === 'plus' ? 1 : ($item->status === 'minus' ? -1 : 0);
                return $sign * $item->qty * $item->wholesale;
            }),
        ];

        $summarySell = [
            'qty' => $filteredStocksPurchase->sum(function ($item) {
                return $item->qty;
            }),
            'total' => $filteredStocksPurchase->sum(function ($item) {
                return $item->qty * $item->pricesale;
            }),
        ];

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

        $productStocks = ProductStock::with('product')->get();

        $sellStocks = $productStocks->filter(function ($stock) {
            return in_array($stock->stockable_type, [Sale::class, SaleDirect::class]);
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

        return view('portal::home', compact('user', 'summaryBuy', 'summarySell', 'summaryBuyMonth', 'summarySellMonth', 'topProducts'));
    }
}
