<?php

namespace Modules\Admin\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Admin\Models\TransactionFloorRoom;
use Modules\Admin\Models\BuildingsLandsFloor;
use Modules\Admin\Models\Room;

class BuildingsLandsRoomController extends Controller
{
    public function index(Request $request){
        $this->authorize('access', TransactionFloorRoom::class);

        return view('admin::inventories.buildingslands.buildingslandsroom.index', [
            'buildingslandsroom_count' => Room::count()
        ]);
    }

}