<?php

namespace Modules\HRMS\Http\Controllers\Service\Leave;

use Modules\Core\Models\CompanyApprovable;
use Modules\HRMS\Models\EmployeeLeave;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Http\Requests\Service\Leave\Approvable\UpdateRequest;

class ApprovableController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeLeave $leave, CompanyApprovable $approvable, UpdateRequest $request)
    {
        $approvable = $leave->approvables()->find($approvable->id);

        $approvable->update($request->transformed()->toArray());

        return redirect()->back()->with('success', 'Berhasil memperbarui status pengajuan, terima kasih!');
    }
}