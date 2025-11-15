<?php

namespace Modules\Admin\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Admin\Models\VehcileSell;

class VehcileSellController extends Controller
{
	 public function index(Request $request)
    {
        $this->authorize('access', Land::class);
        
        return view('admin::inventories.vehcile.vehcile_sell.index', [
            'vehcile_sell_count' => VehcileSell::count()
        ]);
    }
}