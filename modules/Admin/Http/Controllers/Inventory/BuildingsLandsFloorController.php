<?php

namespace Modules\Admin\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Admin\Models\BuildingsLandsFloor;
use Modules\Admin\Models\Floor;

class BuildingsLandsFloorController extends Controller
{
    public function index(Request $request){
        $this->authorize('access', BuildingsLandsFloor::class);

        return view('admin::inventories.buildingslands.buildingslandsfloor.index', [
            'buildingslandsfloor_count' => Floor::count()
        ]);
    }

}