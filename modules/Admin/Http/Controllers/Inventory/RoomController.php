<?php

namespace Modules\Admin\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Admin\Models\Room;

class RoomController extends Controller
{
	 public function index(Request $request)
    {
        $this->authorize('access', Room::class);
        
        return view('admin::inventories.building.room.index', [
            'room_count' => Room::count()
        ]);
    }
}