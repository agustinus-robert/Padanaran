<?php

namespace Modules\HRMS\Http\Controllers\API\Service\Leave;

use Modules\Core\Models\CompanyApprovable;
use Modules\HRMS\Models\EmployeeLeave;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Http\Requests\Service\Leave\Approvable\UpdateRequest;

class ApprovableControllerAPI extends Controller
{
    /**
     * Update the specified approvable resource in storage (API).
     */
    public function update(EmployeeLeave $leave, CompanyApprovable $approvable, UpdateRequest $request)
    {
        // Ambil approvable terkait leave
        $approvableItem = $leave->approvables()->find($approvable->id);

        if (!$approvableItem) {
            return response()->json([
                'success' => false,
                'message' => 'Data approvable tidak ditemukan.'
            ], 404);
        }

        // Update approvable
        $approvableItem->update($request->transformed()->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Berhasil memperbarui status pengajuan.',
            'approvable' => $approvableItem,
        ]);
    }
}
