<?php

namespace Modules\HRMS\Http\Controllers\API\Attendance;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Enums\WorkLocationEnum;
use Modules\Core\Models\CompanyMoment;
use Modules\HRMS\Enums\WorkShiftEnum;
use Modules\HRMS\Models\EmployeeScanLog;
use Modules\HRMS\Models\EmployeeTeacherScanLog;
use Modules\HRMS\Http\Controllers\Controller;

class PresenceControllerAPI extends Controller
{
    /**
     * Display a listing of the resource. (API)
     */
    public function index(Request $request)
    {
        // $isTeacher = !empty($request->input('position'));

        $month = Carbon::parse($request->get('month', now()));
        $start_at = $month->clone()->startOfMonth();
        $end_at   = $month->clone()->endOfMonth();
        $type = 'WFO'; // WFO


        $location = WorkLocationEnum::select($type);
        $workshifts = WorkShiftEnum::cases();
        $moments = CompanyMoment::holiday()
            ->whenMonthOfYear($month)
            ->get()
            ->groupBy('date');


        $employee = $request->user()->employee;
        $schedule = $employee->schedules()
            ->whenMonth($month->format('Y-m'))
            ->first();

        $scanlogs = EmployeeScanLog::where('empl_id', $employee->id)
            ->whereBetween('created_at', [$start_at, $end_at])
            ->where('location', (string) $location->value)
            ->get()
            ->groupBy(fn($log) => $log->created_at->format('Y-m-d'));

        $last_scan = isset($scanlogs[date('Y-m-d')])
            ? $scanlogs[date('Y-m-d')]->last()
            : null;

        $current_schedule = $employee->schedules()
            ->whenMonth(date('Y-m'))
            ->first();

        $vacations = $employee->vacations()
            ->with('quota.category')
            ->whereExtractedDatesBetween(
                $month->startOfMonth()->format("Y-m-d"),
                $month->endOfMonth()->format("Y-m-d")
            )
            ->get()
            ->filter(fn ($vacation) => $vacation->hasAllApprovableResultIn('APPROVE'))
            ->pluck('dates')
            ->filter(fn ($date) => empty(collect($date)->first()['cashable']))
            ->flatten(1)
            ->groupBy('d')
            ->flatten(1)
            ->unique();

        return response()->json([
            'status' => 'success',
            'data' => [
                'month'            => $month->format('Y-m'),
                'location'         => $location,
                'workshifts'       => $workshifts,
                'employee'         => $employee,
                'moments'          => $moments,
                'schedule'         => $schedule,
                'scanlogs'         => $scanlogs,
                'last_scan'        => $last_scan,
                'current_schedule' => $current_schedule,
                'vacations'        => $vacations,
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage. (API)
     */
    public function store(Request $request)
    {
        $employee = $request->user()->employee;

        $payload = [
            'empl_id'     => $employee->id,
            'latlong'     => json_decode($request->input('latlong'), true),
            'location'    => $request->input('location'),
            'ip'          => getClientIp(),
            'user_agent'  => $request->server('HTTP_USER_AGENT')
        ];

        if ($employee->positions->first()->position_id == 14) {
            $input = new EmployeeTeacherScanLog($payload);
        } else {
            $input = new EmployeeScanLog($payload);
        }

        if ($input->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Presensi berhasil disimpan',
                'location_name' => WorkLocationEnum::tryFrom($request->input('location'))?->name,
                'data' => $input
            ], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Gagal menyimpan presensi'
        ], 500);
    }
}
