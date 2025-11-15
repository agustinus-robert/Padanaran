<?php

namespace Modules\Poz\Http\Livewire\Traits;

use Modules\Poz\Models\Product;

trait ProductManageTrait
{
    private function productManage($outletId, $selectedItems = null, $filters = [], $queueItems = [], $helper = [])
    {
        $selectedItems = $selectedItems ?? $this->selectedItems;
        $today = now()->toDateString();

        $query = Product::with([
            'productStockAdjustItems' => function ($query) use ($today) {
                $query->withoutGlobalScopes()
                    ->whereDate('created_at', $today)
                    ->isStock();
            }
        ])
            ->where('name', 'ilike', '%' . $this->query . '%')
            ->whereHas('outlets', function ($query) use ($outletId) {
                $query->where('outlet_id', $outletId);
            });

        foreach ($filters as $key => $value) {
            if ($value !== null && $value != 0) {
                $query->when(true, fn($q) => $q->where($key, $value));
            }
        }

        $productz = $query->get();

        $productz = $productz->map(function ($product) use ($selectedItems, $queueItems, $today, $helper) {
            $arrItem = [];
            $plus = $product->productStockAdjustItems()
                ->isStock()
                ->where('status', 'plus')
                ->sum('qty');

            $minus = $product->productStockAdjustItems()
                ->isStock()
                ->where('status', 'minus')
                ->sum('qty');

            $stock = (int) $plus - (int) $minus;
            $processedKeys = []; 

            foreach ($selectedItems as $index => $item) {
                if ($item['id'] != $product->id) continue;

                $uniqueKey = $item['id'] . '-selected-' . $index;
                if (!in_array($uniqueKey, $processedKeys)) {
                    $stock -= (int) $item['qty'];
                    $processedKeys[] = $uniqueKey;
                }
            }

            if (!empty($queueItems)) {
                foreach ($queueItems as $murid => $products) {
                    if (isset($products[$product->id])) {
                        if (($helper['status'] ?? null) === 'first') {
                            foreach ($products as $key => $value) {
                                if ($key === 'status') continue;
                                $uniqueKey = $value['id'] . '-' . $murid . '-' . $key;

                                if ($value['id'] == $product->id && !in_array($uniqueKey, $processedKeys)) {
                                    $stock -= (int) $value['qty'];
                                    $processedKeys[] = $uniqueKey;

                                }
                            }
                        } else {
                            $uniqueKey = $product->id . '-' . $murid . '-default';
                            if (!in_array($uniqueKey, $processedKeys)) {
                                $qty = (int) ($products[$product->id] ?? 0);
                                $stock -= $qty;
                                $processedKeys[] = $uniqueKey;
                            }
                        }
                    } else {
                        if (($helper['status'] ?? null) === 'first') {
                            if (!empty($products)) {
                                foreach ($products as $key => $value) {
                                    if ($key === 'status') continue;
                                    if ($value['id'] != $product->id) continue;

                                    $uniqueKey = $value['id'] . '-' . $murid . '-' . $key;
                                    if (!in_array($uniqueKey, $processedKeys)) {
                                        $stock -= (int) $value['qty'];
                                        $processedKeys[] = $uniqueKey;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (!empty($helper) && ($helper['status'] ?? null) === 'plus') {
                foreach ($helper as $studentId => $items) {
                    if ($studentId === 'status') continue;

                    foreach ($items as $item) {
                        if ($item['id'] != $product->id) continue;

                        if (isset($queueItems[$studentId])) {
                            $stock += (int)$item['qty'];
                            unset($queueItems[$studentId]);
                        } else {
                            $stock -= (int)$item['qty'];
                        }
                    }
                }
            }

            $product->stock_qty = max($stock, 0);
            return $product;
        });

        return $productz;
    }
}