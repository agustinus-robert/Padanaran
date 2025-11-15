<?php

namespace Modules\Portal\Http\Controllers\Attendance;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Enums\WorkLocationEnum;
use Modules\Core\Models\CompanyMoment;
use Modules\HRMS\Enums\WorkShiftEnum;
use Modules\HRMS\Models\EmployeeScanLog;
use Modules\HRMS\Models\EmployeeTeacherScanLog;
use Modules\Portal\Http\Controllers\Controller;
use PhpParser\JsonDecoder;
use PhpParser\Node\Expr\Cast\Object_;

class PresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $isTeacher = false;
        if(!empty($request->input('position'))){
            $isTeacher = true;
        }
        $month = Carbon::parse($request->get('month', now()));
        $start_at = Carbon::parse($month)->startOfMonth();
        $end_at = Carbon::parse($month)->endOfMonth();

        $location = WorkLocationEnum::select($request->get('type'));
        $workshifts = WorkShiftEnum::cases();
        $moments = CompanyMoment::holiday()->whenMonthOfYear($month)->get()->groupBy('date');

        $employee = $request->user()->employee;
        $schedule = $employee->schedules()->whenMonth($month->format('Y-m'))->first();
        //$scanlogs = $employee->scanlogs()->where('created_at', 'like', $month->format('Y-m-') . '%')->groupBy(fn($log) => $log->created_at->format('Y-m-d'));
        if ($request->position == 'teacher') {
            $scanlogs = EmployeeTeacherScanLog::where('empl_id', $employee->id)
                ->where('created_at', '>=', $start_at)
                ->where('created_at', '<=', $end_at)
                ->where('location', (string) $location->value)
                ->get()
                ->groupBy(fn($log) => $log->created_at->format('Y-m-d'));
        } else {
            $scanlogs = EmployeeScanLog::where('empl_id', $employee->id)
                ->where('created_at', '>=', $start_at)
                ->where('created_at', '<=', $end_at)
                ->where('location', (string) $location->value)
                ->get()
                ->groupBy(fn($log) => $log->created_at->format('Y-m-d'));
        }

        $last_scan = isset($scanlogs[date('Y-m-d')]) ? $scanlogs[date('Y-m-d')]->last() : false;

        $current_schedule = $employee->schedules()->whenMonth(date('Y-m'))->first();

        // Modified
        $vacations = $employee->vacations()
            ->with('quota.category')
            ->whereExtractedDatesBetween($month->startOfMonth()->format("Y-m-d"), $month->endOfMonth()->format("Y-m-d"))
            ->get()
            ->filter(fn($vacation) => $vacation->hasAllApprovableResultIn('APPROVE'))
            ->pluck('dates')
            ->filter(fn($date) => empty(collect($date)->first()['cashable']))
			->flatten(1)
			->groupBy('d')
			->flatten(1)
			->unique();

        return view('portal::attendance.presence.index', compact('month', 'location', 'workshifts', 'employee', 'moments', 'schedule', 'last_scan', 'scanlogs', 'current_schedule', 'vacations', 'isTeacher'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $employee = $request->user()->employee;

        if ($employee->positions->first()->position_id == 14) {
            $input = new EmployeeTeacherScanLog([
                'empl_id'         => $employee->id,
                'latlong'         => json_decode($request->input('latlong'), true),
                'location'         => $request->input('location'),
                'ip'             => getClientIp(),
                'user_agent'    => $request->server('HTTP_USER_AGENT')
            ]);
        } else {
            $input = new EmployeeScanLog([
                'empl_id'         => $employee->id,
                'latlong'         => json_decode($request->input('latlong'), true),
                'location'         => $request->input('location'),
                'ip'             => getClientIp(),
                'user_agent'    => $request->server('HTTP_USER_AGENT')
            ]);
        }

        if ($input->save()) {
            return redirect()->next()->with('success', 'Terima kasih telah melakukan scan presensi <strong>' . WorkLocationEnum::tryFrom($request->input('location'))?->name . '</strong> hari ini, semangat!');
        }

        return redirect()->fail();
    }
}
