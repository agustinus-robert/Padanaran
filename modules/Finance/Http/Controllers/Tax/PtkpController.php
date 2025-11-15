<?php

namespace Modules\Finance\Http\Controllers\Tax;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyPtkp;
use Modules\HRMS\Http\Controllers\Controller;

class PtkpController extends Controller
{
    /**
     * Show the index page.
     */
    public function index(Request $request)
    {
        return view('finance::tax.ptkp.index', [
            'ptkps'      => CompanyPtkp::where('grade_id', userGrades())->whenTrashed($request->get('trashed'))->paginate($request->get('limit', 10)),
            'ptkp_count' => CompanyPtkp::where('grade_id', userGrades())->count(),
        ]);
    }
}
