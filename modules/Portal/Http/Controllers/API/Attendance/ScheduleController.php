<?php

namespace Modules\Portal\Http\Controllers\API\Attendance;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Repositories\ScheduleRepository;

class ScheduleController extends Controller
{
	use ScheduleRepository;
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$employee 	= $request->user()->employee;
		$month 		= Carbon::parse($request->get('month', now()));
		$start_at 	= $month->copy()->startOfMonth()->addDays(20)->format('Y-m-d');
		$end_at 	= $month->copy()->endOfMonth()->addDays(20)->format('Y-m-d');

		return response()->success([
			'message' => 'Berikut jadwal kamu periode ini.',
			'schedules' => $employee->contract->schedules()->where('start_at', $start_at)->where('end_at', $end_at)->first(),
			'current_schedule' => $employee->contract->schedules()->whenMonth(date('Y-m'))->first()
		]);
	}

	/**
	 * Display a listing of the resource.
	 */
	public function show(Request $request)
	{
		$employee 	= $request->user()->employee;
		$today		= Carbon::parse(now())->format('Y-m-d');
		$start_at 	= now()->copy()->startOfMonth()->format("Y-m-d");
		$end_at 	= now()->copy()->endOfMonth()->format("Y-m-d");
		$refSchedules 	= $employee->contract->schedules()->wherePeriodIn($start_at, $end_at)->get();

		return response()->success([
			'message' => 'Berikut jadwal kamu hari ini.',
			'today' => $this->renderSchedule($refSchedules, $start_at, $end_at)[$today],
		]);
	}
}
