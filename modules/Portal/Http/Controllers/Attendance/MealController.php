<?php

namespace Modules\Portal\Http\Controllers\Attendance;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Enums\WorkLocationEnum;
use Modules\Core\Models\CompanyMoment;
use Modules\HRMS\Enums\WorkShiftEnum;
use Modules\HRMS\Models\EmployeeMealScanLog;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Repositories\ScheduleRepository;
use Modules\Portal\Repositories\ServiceRepository;

class MealController extends Controller
{
	use ScheduleRepository, ServiceRepository;

	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$employee 	= $request->user()->employee;

		$month 		= Carbon::parse($request->get('month', now()));
		$location 	= WorkLocationEnum::select($request->get('type'));
		$workshifts = WorkShiftEnum::cases();
		$moments 	= CompanyMoment::holiday()->whenMonthOfYear($month)->get()->groupBy('date');

		$start_at 	= $month->copy()->startOfMonth()->format("Y-m-d");
		$end_at 	= $month->copy()->endOfMonth()->format("Y-m-d");

		$schedule   = $employee->contract->schedules()->wherePeriodIn(now()->copy()->startOfMonth()->format("Y-m-d"), now()->copy()->endOfMonth()->format("Y-m-d"))->get();
		$scanlogs 	= $employee->mealScanLogs()->where('created_at', 'like', $month->format('Y-m-') . '%')->get()->groupBy(fn ($log) => $log->created_at->format('Y-m-d'));
		$last_scan 	= isset($scanlogs[date('Y-m-d')]) ? $scanlogs[date('Y-m-d')]->last() : false;

		// Current schedule
		$current_schedule = $this->renderSchedule($schedule, $start_at, $end_at);
		$count_schedule   = $this->countSchedule($current_schedule);

		// vacation
		$vacations 		= $this->getCurrentEmployeeVacation($employee);

		// holidays
		$holidays 		= $this->getCurrentEmployeeHoliday($employee);

		return view('portal::attendance.meal.index', compact('month', 'workshifts', 'employee', 'moments', 'schedule', 'last_scan', 'scanlogs', 'current_schedule', 'vacations', 'holidays', 'location', 'count_schedule'));
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$employee = $request->user()->employee;

		$employee->mealScanLogs()->save(new EmployeeMealScanLog([
			'empl_id' 		=> $employee->id,
			'latlong' 		=> json_decode($request->input('latlong'), true),
			'location' 		=> $request->input('location'),
			'ip' 			=> getClientIp(),
			'user_agent'	=> $request->server('HTTP_USER_AGENT')
		]));

		return redirect()->next()->with('success', 'Terima kasih telah melakukan scan presensi makan hari ini, semangat makannya ya!');
	}
}
