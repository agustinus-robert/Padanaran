<?php

namespace Modules\Reference\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\Reference\Http\Controllers\Controller;
use Modules\Reference\Models\Grade;

class GradeController extends Controller
{
    /**
     * Display a listing of the resources..
     */
    public function index(Request $request)
    {
        return response()->success([
            'message' => 'Berikut adalah data referensi nomor telpon',
            'data' => Grade::all()
        ]);
    }
}
