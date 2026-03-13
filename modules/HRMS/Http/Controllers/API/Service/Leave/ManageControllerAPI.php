<?php

namespace Modules\HRMS\Http\Controllers\API\Service\Leave;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Models\EmployeeLeave;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Http\Requests\Service\Leave\Manage\UpdateRequest;

class ManageControllerAPI extends Controller
{
    /**
     * Display a listing of the resource (API JSON).
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeLeave::class);

        $start_at = $request->get('start_at', date('Y-m-01'));
        $end_at   = $request->get('end_at', date('Y-m-t'));
        $limit    = $request->get('limit', 10);

        $departments = CompanyDepartment::visible()
            ->with('positions')
            ->get();

        $leaves = EmployeeLeave::with('employee.user', 'category', 'approvables.userable.position')
            ->whereHas('employee')
            ->whenPeriod($start_at, $end_at)
            ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
            ->whenWithTrashed($request->get('trashed'))
            ->search($request->get('search'))
            ->latest()
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'departments' => $departments,
            'leaves' => $leaves,
            'start_at' => $start_at,
            'end_at' => $end_at,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeLeave $leave)
    {
        $this->authorize('store', EmployeeLeave::class);

        $leave = $leave->load('employee.user', 'approvables.userable.position');
        $employee = $leave->employee;

        $results = config('modules.core.features.services.leaves.approvable_enum_available');

        return response()->json([
            'success' => true,
            'leave' => $leave,
            'employee' => $employee,
            'results' => $results,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeLeave $leave, UpdateRequest $request)
    {
        $this->authorize('update', $leave);

        $leave->update($request->transform());

        return response()->json([
            'success' => true,
            'message' => 'Berhasil memperbarui detail pengajuan cuti',
            'leave' => $leave,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeLeave $leave)
    {
        $this->authorize('destroy', $leave);

        $tmp = $leave;
        $leave->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan cuti ' . $tmp->employee->user->name . ' berhasil dihapus',
        ]);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(EmployeeLeave $leave)
    {
        $this->authorize('restore', $leave);

        $leave->restore();

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan cuti ' . $leave->employee->user->name . ' berhasil dipulihkan',
            'leave' => $leave,
        ]);
    }
}
