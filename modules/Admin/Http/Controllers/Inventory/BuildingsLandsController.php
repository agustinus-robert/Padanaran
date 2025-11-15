<?php

namespace Modules\Admin\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Admin\Models\BuildingsLandsSell;

class BuildingsLandsController extends Controller
{
	 public function index(Request $request)
    {
        $this->authorize('access', BuildingsLandsSell::class);
        
        return view('admin::inventories.buildingslands.index', [
            'buildingslands_count' => BuildingsLandsSell::where('status', 1)->count()
        ]);
    }
}