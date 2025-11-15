<?php

namespace Modules\Portal\Http\Controllers\API\Attendance;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\HRMS\Models\EmployeeScanLog;
use Modules\Portal\Http\Controllers\Controller;

class PresenceController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$employee = $request->user()->employee;
		$month = Carbon::parse($request->get('month', now()));

		return response()->success([
			'message' 	=> 'Berikut adalah daftar presensi berdasarkan kueri Anda.',
			'scanlogs' 	=> $employee->scanlogs()->where('created_at', 'like', $month->format('Y-m-') . '%')->get()->groupBy(fn ($log) => $log->created_at->format('Y-m-d')),
			'last_scan' => isset($scanlogs[date('Y-m-d')]) ? $scanlogs[date('Y-m-d')]->last() : false,
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$employee = $request->user()->employee;

		$employee->scanlogs()->save(new EmployeeScanLog([
			'empl_id' 		=> $employee->id,
			'latlong' 		=> json_decode($request->input('latlong'), true),
			'location' 		=> $request->input('location'),
			'ip' 			=> getClientIp(),
			'user_agent'	=> $request->server('HTTP_USER_AGENT')
		]));

		return response()->success([
			'message' 	=> 'Terima kasih telah melakukan scan presensi.',
		]);
	}
}
