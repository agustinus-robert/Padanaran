<?php

namespace Modules\HRMS\Http\Controllers\API\Service\Vacation;

use Illuminate\Http\Request;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Models\EmployeeVacation;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Http\Requests\Service\Vacation\Manage\UpdateRequest;

class ManageControllerAPI extends Controller
{
    /**
     * Display a listing of the resource (API JSON).
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeVacation::class);

        $start_at = $request->get('start_at', date('Y-m-01'));
        $end_at   = $request->get('end_at', date('Y-m-t'));
        $limit    = $request->get('limit', 10);

        $departments = CompanyDepartment::where('grade_id', userGrades())
            ->visible()
            ->with('positions')
            ->get();

        $vacations = EmployeeVacation::with('quota.employee.user', 'quota.category', 'approvables.userable.position')
            ->where('grade_id', userGrades())
            ->whenPeriod($start_at, $end_at)
            ->whenWithTrashed($request->get('trashed'))
            ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
            ->search($request->get('search'))
            ->latest()
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'departments' => $departments,
            'vacations' => $vacations,
            'start_at' => $start_at,
            'end_at' => $end_at,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeVacation $vacation)
    {
        $this->authorize('store', EmployeeVacation::class);

        $vacation = $vacation->load('quota.employee.user', 'approvables.userable.position');
        $employee = $vacation->quota->employee;

        $results = ApprovableResultEnum::cases();

        return response()->json([
            'success' => true,
            'vacation' => $vacation,
            'employee' => $employee,
            'results' => $results,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeVacation $vacation, UpdateRequest $request)
    {
        $this->authorize('update', $vacation);

        $data = array_merge($request->transformed()->toArray(), [
            'grade_id' => userGrades()
        ]);

        $vacation->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil memperbarui detail pengajuan.',
            'vacation' => $vacation,
        ]);
    }

    /**
     * Change approvable status to cancelable/not.
     */
    public function change(EmployeeVacation $vacation)
    {
        $approvable = $vacation->approvables->first();
        if (!$approvable) {
            return response()->json([
                'success' => false,
                'message' => 'Approable tidak ditemukan.'
            ], 404);
        }

        $current = $approvable->cancelable ?? false;

        $vacation->approvables()->update([
            'cancelable' => !($current),
            'result' => 0,
            'reason' => null,
            'history' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengubah status menjadi ' . ($current ? 'pengajuan' : 'pembatalan') . '.',
            'approvable' => $vacation->approvables()->first(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeVacation $vacation)
    {
        $this->authorize('destroy', $vacation);

        $tmp = $vacation;
        $vacation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan cuti ' . $tmp->quota->employee->user->name . ' berhasil dihapus.',
        ]);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(EmployeeVacation $vacation)
    {
        $this->authorize('restore', $vacation);

        $vacation->restore();

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan cuti ' . $vacation->quota->employee->user->name . ' berhasil dipulihkan.',
            'vacation' => $vacation,
        ]);
    }
}
