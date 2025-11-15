<?php

namespace Modules\HRMS\Http\Controllers\API;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\Core\Models\CompanyPosition;

class PositionController extends Controller
{

    /**
     * Fetch all empls.
     */
    public function all(Request $request)
    {
        $position = CompanyPosition::get();

        $tree = DB::table('cmp_position_trees')->get();

        return response()->success([
            'message' => 'Berikut adalah daftar semua jabatan yang ada.',
            'position' => $position,
            'tree' => $tree
        ]);
    }
}
