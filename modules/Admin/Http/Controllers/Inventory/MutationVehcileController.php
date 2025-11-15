<?php

namespace Modules\Admin\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Admin\Models\MutationVehcile;

class MutationVehcileController extends Controller
{
	 public function index(Request $request)
    {
        $this->authorize('access', MutationVehcile::class);
        
        return view('admin::inventories.vehcile.mutation.index', [
            'mutation_vehcile_count' => MutationVehcile::count()
        ]);
    }
}