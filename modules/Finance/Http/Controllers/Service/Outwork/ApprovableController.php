<?php

namespace Modules\Finance\Http\Controllers\Service\Outwork;

use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyApprovable;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\Finance\Http\Requests\Service\Outwork\Approvable\UpdateRequest;
use Modules\HRMS\Models\EmployeeOutwork;

class ApprovableController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeOutwork $outwork, CompanyApprovable $approvable, UpdateRequest $request)
    {
        $approvable = $outwork->approvables()->find($approvable->id);

        $approvable->update($request->transformed()->toArray());

        $approvable->modelable->update(['paidable_at' => $approvable->result === ApprovableResultEnum::APPROVE && $approvable->is($approvable->modelable->approvables->sortByDesc('level')->first()) ? now() : null]);

        return redirect()->back()->with('success', 'Berhasil memperbarui status pengajuan, terima kasih!');
    }
}
