<?php

namespace Modules\Reference\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\Reference\Http\Controllers\Controller;
use Modules\Reference\Models\Country;

class PhoneController extends Controller
{
    /**
     * Display a listing of the resources..
     */
    public function index(Request $request)
    {
        return response()->success([
            'message' => 'Berikut adalah data referensi nomor telpon',
            'data' => Country::select('phones', 'kd')->get()->pluck('phones', 'kd')
        ]);
    }
}
