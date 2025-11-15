<?php

namespace Modules\Portal\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\HRMS\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    /**
     * Display employee data
     */
    public function index(Request $request)
    {
        return response()->success([
            'message' => 'Berikut adalah informasi akun Anda.',
            'employee' => $request->user()->employee ? $request->user()->employee->load('contract', 'position.position') : null,
        ]);
    }
}
