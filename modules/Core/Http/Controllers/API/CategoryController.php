<?php

namespace Modules\Core\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\CompanyInventory;

class CategoryController extends Controller
{
    /**
     * Search a listing of the resources..
     */
    public function category(Request $request)
    {
        return response()->success([
            'message' => 'Berikut adalah data hasil pencarian kategori dengan query "' . $request->get('q', '') . '"',
            'data'    => CompanyInventory::distinct()->where('category', 'like', '%' . $request->get('q') . '%')->get()
                ->map(function ($inventory) {
                    return [
                        'id'    => $inventory->category,
                        'text'  => $inventory->category
                    ];
                })
        ]);
    }

    public function brand(Request $request)
    {
        return response()->success([
            'message' => 'Berikut adalah data hasil pencarian kategori dengan query "' . $request->get('q', '') . '"',
            'data'    => CompanyInventory::distinct()->where('brand', 'like', '%' . $request->get('q') . '%')->get()
                ->map(fn ($inventory) => [
                    'id' => $inventory->brand,
                    'text' => $inventory->brand,
                ])
        ]);
    }

    public function count(Request $request)
    {
        return response()->success([
            'message' => 'Berikut adalah data hasil pencarian kategori dengan query "' . $request->get('q', '') . '"',
            'data'    => CompanyInventory::distinct()
                ->whereYear('bought_at', $request->get('y'))
                ->where('category', $request->get('q'))
                // ->whereJsonContains('meta->register', $request->get('r'))
                ->get()
                ->map(fn ($inventory) => [
                    'id' => $inventory->id ?? '',
                    'name' => $inventory->name ?? '',
                    'category' => $inventory->category ?? '',
                    'bought_at' => $inventory->bought_at ?? '',
                ])
        ]);
    }
}
