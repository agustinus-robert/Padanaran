<?php

namespace Modules\Finance\Http\Controllers\Service\Overtime;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Models\EmployeeOvertime;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Http\Requests\Service\Overtime\Manage\UpdateRequest;

class ManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start_at = $request->get('start_at', date('Y-m-01')) . ' 00:00:00';
        $end_at = $request->get('end_at', date('Y-m-t')) . ' 23:59:59';

        $departments = CompanyDepartment::where('grade_id', userGrades())->visible()->with('positions')->get();

        $overtimes = EmployeeOvertime::with('approvables.userable.position', 'employee.user')
            ->where('grade_id', userGrades())
            ->whenPeriod($start_at, $end_at)
            ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
            ->whenOnlyPending($request->get('pending'))
            ->search($request->get('search'))
            ->latest()
            ->paginate($request->get('limit', 10));

        $pending_overtimes_count = EmployeeOvertime::where('grade_id', userGrades())->whenOnlyPending(true)->count();

        return view('finance::service.overtime.manage.index', compact('start_at', 'end_at', 'departments', 'overtimes', 'pending_overtimes_count'));
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeOvertime $overtime)
    {
        $overtime = $overtime->load('employee.user', 'approvables.userable.position');
        $employee = $overtime->employee;

        $results = config('modules.core.features.services.overtimes.approvable_enum_available');

        return view('finance::service.overtime.manage.show', compact('employee', 'overtime', 'results'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeOvertime $overtime, UpdateRequest $request)
    {
        $overtime->update($request->transformed()->toArray());

        return redirect()->back()->with('success', 'Berhasil memperbarui detail pengajuan, terima kasih!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeOvertime $overtime)
    {
        $tmp = $overtime;
        $overtime->delete();

        return redirect()->back()->with('success', 'Pengajuan overtime <strong>' . $tmp->employee->user->name . '</strong> berhasil dihapus');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(EmployeeOvertime $overtime)
    {
        $overtime->restore();

        return redirect()->back()->with('success', 'Pengajuan overtime <strong>' . $overtime->employee->user->name . '</strong> berhasil dipulihkan');
    }
}
