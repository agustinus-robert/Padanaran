<?php

namespace Modules\Portal\Http\Controllers\Vacation\Cashable;

use Illuminate\Http\Request;
use Modules\HRMS\Models\EmployeeVacation;
use Modules\Core\Models\CompanyApprovable;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Vacation\Manage\UpdateRequest;

class ManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeVacation $vacation, Request $request)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyApprovable $approvable, UpdateRequest $request)
    {
    }
}
