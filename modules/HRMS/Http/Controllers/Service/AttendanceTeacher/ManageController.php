<?php

namespace Modules\HRMS\Http\Controllers\Service\AttendanceTeacher;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Models\CompanyMoment;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Models\EmployeeTeacherScanLog;
use Modules\HRMS\Models\EmployeeScheduleTeacher;
use Modules\HRMS\Http\Controllers\Controller;

class ManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeScheduleTeacher::class);

        $start_at = $request->get('start_at', date('Y-m-01')) . ' 00:00:00';
        $end_at = $request->get('end_at', date('Y-m-t')) . ' 23:59:59';

        $moments = CompanyMoment::holiday()->whereBetween('date', [$start_at, $end_at])->get()->groupBy('date');
        $departments = CompanyDepartment::visible()->with('positions')->get();

        $scanlogs = EmployeeTeacherScanLog::where('created_at', '>=', Carbon::parse($start_at))
            ->where('created_at', '<=', Carbon::parse($end_at))
            ->get();

        $schedules = EmployeeScheduleTeacher::with(['employee.contract' => fn($query) => $query->with('employee.user', 'position.position')->orderBy('empl_id', 'ASC')])
            ->whereHas('employee.contract')
            ->whereBetween('start_at', [$start_at, $end_at])->orWhereBetween('end_at', [$start_at, $end_at])->orWhereMonthOfYear($start_at)->orWhereMonthOfYear($end_at)
            ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
            ->search($request->get('search'))
            ->paginate($request->get('limit', 10));

        return view('hrms::service.attendance-teacher.manage.index', compact('start_at', 'end_at', 'schedules', 'scanlogs', 'moments', 'departments'));
    }
}
