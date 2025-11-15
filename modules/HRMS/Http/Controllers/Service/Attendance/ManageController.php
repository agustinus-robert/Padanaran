<?php

namespace Modules\HRMS\Http\Controllers\Service\Attendance;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Models\CompanyMoment;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Models\EmployeeScanLog;
use Modules\HRMS\Models\EmployeeSchedule;
use Modules\HRMS\Http\Controllers\Controller;

class ManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeScanLog::class);

        $start_at = $request->get('start_at', date('Y-m-01')) . ' 00:00:00';
        $end_at = $request->get('end_at', date('Y-m-t')) . ' 23:59:59';

        $moments = CompanyMoment::holiday()->whereBetween('date', [$start_at, $end_at])->get()->groupBy('date');
        $departments = CompanyDepartment::where('grade_id', userGrades())->visible()->with('positions')->get();

        $scanlogs = EmployeeScanLog::where('created_at', '>=', Carbon::parse($start_at))
            ->whereHas('employee', function ($q) {
                $q->where('grade_id', userGrades()); 
            })
            ->where('created_at', '<=', Carbon::parse($end_at))
            ->get();

        $schedules = EmployeeSchedule::with(['employee.contract' => fn($query) => $query->with('employee.user', 'position.position')->orderBy('empl_id', 'ASC')])
            ->whereHas('employee.contract')
            ->whereHas('employee', function ($q) {
                $q->where('grade_id', userGrades()); 
            })
            ->whereBetween('start_at', [$start_at, $end_at])->orWhereBetween('end_at', [$start_at, $end_at])->orWhereMonthOfYear($start_at)->orWhereMonthOfYear($end_at)
            ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
            ->search($request->get('search'))
            ->paginate($request->get('limit', 10));

        return view('hrms::service.attendance.manage.index', compact('start_at', 'end_at', 'schedules', 'scanlogs', 'moments', 'departments'));
    }
}
