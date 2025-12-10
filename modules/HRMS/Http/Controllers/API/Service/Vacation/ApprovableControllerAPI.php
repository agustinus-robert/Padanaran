<?php

namespace Modules\HRMS\Http\Controllers\API\Service\Vacation;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyApprovable;
use Modules\HRMS\Models\EmployeeVacation;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Http\Requests\Service\Vacation\Approvable\UpdateRequest;

class ApprovableControllerAPI extends Controller
{
    /**
     * Update the specified approvable resource in storage (API).
     */
    public function update(EmployeeVacation $vacation, CompanyApprovable $approvable, UpdateRequest $request)
    {
        // Ambil approvable terkait vacation
        $approvableItem = $vacation->approvables()->find($approvable->id);

        if (!$approvableItem) {
            return response()->json([
                'success' => false,
                'message' => 'Approable tidak ditemukan.'
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
