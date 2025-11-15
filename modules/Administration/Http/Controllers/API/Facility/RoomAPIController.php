<?php

namespace Modules\Administration\Http\Controllers\API\Facility;

use Illuminate\Http\Request;
use Modules\Administration\Http\Controllers\Controller;
use Modules\Administration\Models\SchoolBuildingRoom;
use Modules\Administration\Models\SchoolBuilding;
use Auth;

class RoomAPIController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Gunakan Bearer Token
    }

    /**
     * List rooms with pagination
     */
    public function index(Request $request)
    {
        $this->authorize('access', SchoolBuildingRoom::class);

        $trashed = $request->get('trash', 0);
        $search = $request->get('search', '');
        $limit  = $request->get('limit', 10);

        $rooms = SchoolBuildingRoom::with('building')
            ->where('name', 'like', '%'.$search.'%')
            ->when($trashed, fn($query) => $query->onlyTrashed())
            ->paginate($limit);

        
        return response()->json([
            'success' => true,
            'data' => $rooms
        ]);
    }
}
