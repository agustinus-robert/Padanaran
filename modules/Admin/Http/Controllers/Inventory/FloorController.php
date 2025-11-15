<?php

namespace Modules\Admin\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Admin\Models\Floor;

class FloorController extends Controller
{
	 public function index(Request $request)
    {
        $this->authorize('access', Floor::class);
        
        return view('admin::inventories.building.floor.index', [
            'floor_count' => Floor::count()
        ]);
    }
}