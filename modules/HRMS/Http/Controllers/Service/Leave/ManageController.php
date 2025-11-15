<?php

namespace Modules\HRMS\Http\Controllers\Service\Leave;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Models\EmployeeLeave;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Http\Requests\Service\Leave\Manage\UpdateRequest;

class ManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeLeave::class);

        $start_at = $request->get('start_at', date('Y-m-01'));
        $end_at = $request->get('end_at', date('Y-m-t'));

        $departments = CompanyDepartment::where('grade_id', userGrades())->visible()->with('positions')->get();

        $leaves = EmployeeLeave::with('employee.user', 'category', 'approvables.userable.position')
            ->whereHas('employee', function($query){
                $query->where('grade_id', userGrades());
            })
            ->whenPeriod($start_at, $end_at)
            ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
            ->whenWithTrashed($request->get('trashed'))
            ->search($request->get('search'))
            ->latest()
            ->paginate($request->get('limit', 10));

        return view('hrms::service.leave.manage.index', compact('start_at', 'end_at', 'departments', 'leaves'));
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

        return view('hrms::service.leave.manage.show', compact('employee', 'leave', 'results'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeLeave $leave, UpdateRequest $request)
    {
        $this->authorize('update', $leave);

        $leave->update($request->transform());

        return redirect()->back()->with('success', 'Berhasil memperbarui detail pengajuan, terima kasih!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeLeave $leave)
    {
        $this->authorize('destroy', $leave);

        $tmp = $leave;
        $leave->delete();

        return redirect()->back()->with('success', 'Pengajuan cuti <strong>' . $tmp->employee->user->name . '</strong> berhasil dihapus');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(EmployeeLeave $leave)
    {
        $this->authorize('restore', $leave);

        $leave->restore();

        return redirect()->back()->with('success', 'Pengajuan cuti <strong>' . $leave->employee->user->name . '</strong> berhasil dipulihkan');
    }
}
