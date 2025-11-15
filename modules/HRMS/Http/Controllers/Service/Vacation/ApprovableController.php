<?php

namespace Modules\HRMS\Http\Controllers\Service\Vacation;

use Illuminate\Http\Request;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyApprovable;
use Modules\HRMS\Models\EmployeeVacation;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Http\Requests\Service\Vacation\Approvable\UpdateRequest;

class ApprovableController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeVacation $vacation, CompanyApprovable $approvable, UpdateRequest $request)
    {
        $approvable = $vacation->approvables()->find($approvable->id);

        $approvable->update($request->transformed()->toArray());

        return redirect()->back()->with('success', 'Berhasil memperbarui status pengajuan, terima kasih!');
    }
}