<?php

namespace Modules\HRMS\Http\Controllers\Service\Overtime;

use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyApprovable;
use Modules\HRMS\Models\EmployeeOvertime;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Http\Requests\Service\Overtime\Approvable\UpdateRequest;

class ApprovableController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeOvertime $overtime, CompanyApprovable $approvable, UpdateRequest $request)
    {
        $approvable = $overtime->approvables()->find($approvable->id);

        $approvable->update($request->transformed()->toArray());

        $approvable->modelable->update(['paidable_at' => $approvable->result === ApprovableResultEnum::APPROVE && $approvable->is($approvable->modelable->approvables->sortByDesc('level')->first()) ? now() : null]);

        return redirect()->back()->with('success', 'Berhasil memperbarui status pengajuan, terima kasih!');
    }
}
