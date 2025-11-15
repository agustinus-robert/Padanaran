<?php

namespace Modules\Administration\Http\Controllers\API\Facility;

use Auth;
use Illuminate\Http\Request;
use Modules\Administration\Http\Controllers\Controller;
use Modules\Administration\Models\SchoolBuilding;
use App\Models\District;

class BuildingAPIController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Gunakan Bearer Token
    }

    /**
     * List buildings with pagination
     */
    public function index(Request $request)
    {
        $this->authorize('access', SchoolBuilding::class);

        $trashed = $request->get('trash', 0);
        $search  = $request->get('search', '');
        $limit   = $request->get('limit', 10);

        $buildings = SchoolBuilding::where('grade_id', userGrades())
            ->where('name', 'like', '%'.$search.'%')
            ->when($trashed, fn($query) => $query->onlyTrashed())
            ->paginate($limit);

        $districts = District::whereIn('regency_id', [3401, 3402, 3403, 3404, 3471])->get();

        return response()->json([
            'success' => true,
            'date' => $buildings
        ]);
    }
}
