<?php

namespace Modules\HRMS\Http\Controllers\Summary;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Core\Enums\WorkLocationEnum;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\Core\Models\CompanyMoment;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Models\EmployeeScanLog;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeDataRecapitulation;
use Modules\HRMS\Http\Requests\Summary\Attendance\StoreRequest;
use Modules\HRMS\Http\Requests\Summary\Attendance\UpdateRequest;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeDataRecapitulation::class);

        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        $departments = CompanyDepartment::where('grade_id', userGrades())->visible()->with('positions')->get();

        $summaries = EmployeeDataRecapitulation::whereHas('employee', function($query){
           $query->where('grade_id', userGrades());
        })->whereType(DataRecapitulationTypeEnum::ATTENDANCE)->whereStrictPeriodIn($start_at, $end_at)->get();

        $employees = Employee::with('user', 'contract.position.position')
            ->whereHas('contract.position', function ($position) {
                $position->whereHas('position', function ($type) {
                    $type->whereIn('type', [
                        PositionTypeEnum::KEPALASEKOLAH,
                        PositionTypeEnum::HUMAS,
                        PositionTypeEnum::TATAUSAHA,
                        // PositionTypeEnum::GURU,
                        PositionTypeEnum::GURUBK
                    ]);
                });
            })
            ->where('grade_id', userGrades())
            ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
            ->search($request->get('search'))
            ->paginate($request->get('limit', 10));

        return view('hrms::summary.attendances.index', compact('start_at', 'end_at', 'departments', 'summaries', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $employee = Employee::where('grade_id', userGrades())->findOrFail($request->get('employee'));

        foreach (WorkLocationEnum::cases() as $location) {
            $locations[$location->value] = $location->name;
        }

        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        $leaves = $employee->leaves()->with('approvables')->whereExtractedDatesBetween($start_at, $end_at)->get()->filter(fn($leave) => $leave->hasAllApprovableResultIn('APPROVE'))->unique('id');
        $vacations = $employee->vacations()->with('approvables', 'quota.category')->whereExtractedDatesBetween($start_at, $end_at)->get()->filter(fn($vacation) => $vacation->hasAllApprovableResultIn('APPROVE'))->unique('id');
        $overtimes = $employee->overtimes()->with('approvables')->whereExtractedDatesBetween($start_at, $end_at)->get()->filter(fn($vacation) => $vacation->hasAllApprovableResultIn('APPROVE'))->unique('id')->flatMap(fn($overtime) => $overtime->dates->map(
            fn($date) => [
                'date' => $date['d'],
                'start_at' => Carbon::parse($date['d'] . ' ' . $date['t_s']),
                'end_at' => Carbon::parse($date['d'] . ' ' . $date['t_e'])
            ]
        ))->filter(fn($overtime) => $start_at->lte($overtime['date']) && $end_at->gte($overtime['date']));

        $moments = CompanyMoment::holiday()->whereBetween('date', [$start_at, $end_at])->get();

        $scanlogs = $employee->scanlogs()
            ->where('created_at', '>=', $start_at)
            ->where('created_at', '<=', $end_at)
            ->groupBy(fn($log) => $log->created_at->format('Y-m-d'));

        $schedules = $employee->schedules()->wherePeriodIn($start_at, $end_at)->get()->each(function ($schedule) use ($scanlogs) {
            $schedule->entries = $schedule->getEntryLogs($scanlogs);
            return $schedule;
        });

        $entries = $schedules->pluck('entries')->mapWithKeys(fn($k) => $k)->filter(fn($v, $k) => $start_at->lte(Carbon::parse($k)) && $end_at->gte(Carbon::parse($k)));

        $workDays = $start_at->diffInDaysFiltered(function (Carbon $date) use ($moments) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), ($moments->pluck('date')->toArray()));
        }, $end_at);

        $presences = $entries->flatten(1)->filter(function ($e) {
            return $e->bool === true;
        });

        $adtList = CarbonPeriod::create($start_at, $end_at)->filter(function ($period) use ($employee) {
            return $period->dayOfWeekIso == $employee->getMeta('additional_days') && $period->gte('2023-08-01 01:00:00');
        });

        foreach ($adtList as $value) {
            $list[] = $value->format('Y-m-d');
        }

        $adtDays = (!isset($list))  ? 0 : $entries->flatten(1)->filter(fn($e) => in_array($e->date, $list))->count();

        $overtime_days = $presences->count() >= $workDays ? $presences->take(($presences->count() - $workDays) * -1) : collect([]);
        $overtime_holidays = $entries->flatten()->whereIn('date', $moments->pluck('date'))->values();

        return view('hrms::summary.attendances.create', compact('employee', 'start_at', 'end_at', 'locations', 'leaves', 'vacations', 'overtimes', 'moments', 'schedules', 'entries', 'overtime_days', 'overtime_holidays', 'adtDays', 'workDays', 'presences', 'adtList', 'scanlogs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Employee $employee, StoreRequest $request)
    {
        $summary = array_merge(
            $request->transformed()->toArray(),
            [
                'type' => DataRecapitulationTypeEnum::ATTENDANCE,
                'empl_id' => $employee->id
            ]
        );

        if (EmployeeDataRecapitulation::updateOrCreate(Arr::only($summary, ['empl_id', 'type', 'start_at', 'end_at']), $summary)) {
            $request->user()->log('melakukan rekapitulasi presensi <strong>' . $employee->user->name . '</strong>', Employee::class, $employee->id);
            return redirect()->next()->with('success', 'Rekapitulasi presensi <strong>' . $employee->user->name . '</strong> berhasil disimpan.');
        }
        return redirect()->fail();
    }

    /* *
     * edit recaps
     */
    public function show(EmployeeDataRecapitulation $attendance, Request $request)
    {
        // return $attendance;
        $employee = Employee::findOrFail($attendance->empl_id);

        foreach (WorkLocationEnum::cases() as $location) {
            $locations[$location->value] = $location->name;
        }

        $scanlogs = $employee->scanlogs()->whereBetween('created_at', [$attendance->start_at, $attendance->end_at->copy()->endOfDay()])->groupBy(fn($log) => $log->created_at->format('Y-m-d'));

        $schedules = $employee->schedules()->wherePeriodIn($attendance->start_at, $attendance->end_at)->get()->each(function ($schedule) use ($scanlogs) {
            $schedule->entries = $schedule->getEntryLogs($scanlogs);
            return $schedule;
        });

        $entries = $schedules->pluck('entries')->mapWithKeys(fn($k) => $k)->filter(fn($v, $k) => $attendance->start_at->lte(Carbon::parse($k)) && $attendance->end_at->gte(Carbon::parse($k)));

        return view('hrms::summary.attendances.edit', [
            'attendance' => $attendance,
            'entries' => $entries,
            'locations' => $locations,
            'scanlogs' => $scanlogs,
            'moments' => CompanyMoment::holiday()->whereBetween('date', [$attendance->start_at, $attendance->end_at])->get()
        ]);
    }

    public function update(EmployeeDataRecapitulation $attendance, Employee $employee, UpdateRequest $request)
    {
        if ($employee) {
            $attendance->fill($request->transformed()->toArray());
            if ($attendance->save()) {
                $request->user()->log('memperbarui rekapitulasi presensi <strong>' . $attendance->employee->user->name . '</strong>', Employee::class, $attendance->empl_id);
                return redirect()->next()->with('success', 'Rekapitulasi presensi <strong>' . $attendance->employee->user->name . '</strong> berhasil diperbarui.');
            }
            return redirect()->fail();
        }
        return redirect()->fail();
    }
}
