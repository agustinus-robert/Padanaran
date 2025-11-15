<?php

namespace Modules\Admin\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\Admin\Models\Inventory;
use Modules\Reference\Http\Controllers\Controller;

class InventoriesController extends Controller
{
    /**
     * Search a listing of the resources..
     */
    public function search(Request $request)
    {
        $data = Inventory::with('items')->search($request->get('q'))->get();

        return response()->success([
            'message' => 'Berikut adalah data hasil pencarian dengan query "' . $request->get('q', '') . '"',
            'data' => $data->map(fn ($v) => [
                'id' => $v->id,
                'text' => $v->name,
                'items' => $v->items
            ])
        ]);
    }
}
