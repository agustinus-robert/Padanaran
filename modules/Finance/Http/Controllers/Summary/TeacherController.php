<?php

namespace Modules\Finance\Http\Controllers\Summary;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\Core\Enums\WorkLocationEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\Core\Models\CompanyMoment;
use Modules\Core\Models\CompanyPosition;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeePosition;
use Modules\HRMS\Models\EmployeeDataRecapitulation;
use Modules\Administration\Http\Controllers\Controller;
use Modules\HRMS\Http\Requests\Summary\Attendance\StoreRequest;
use Modules\HRMS\Http\Requests\Summary\Attendance\UpdateRequest;
use Modules\HRMS\Models\EmployeeRecapSubmission;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyApprovable;
use Modules\HRMS\Http\Requests\Teacher\SubmissionUpdateRequest;
use Modules\HRMS\Models\EmployeeScheduleShiftDuty;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeRecapSubmission::class);
        $user     = $request->user();
        $employee = $user->employee->load('position.position.children');
        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at   = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        $departments = CompanyDepartment::whereIn(
            'id',
            CompanyPosition::whereType(PositionTypeEnum::GURU)
                ->pluck('dept_id')->unique()->toArray()
        )->where('grade_id', userGrades())->visible()->with(['positions' => fn($poss) => $poss->whereType(PositionTypeEnum::GURU)])->get();

        $summaries = EmployeeDataRecapitulation::whereHas('employee', function($query){
            $query->where('grade_id', userGrades());
        })->whereType(DataRecapitulationTypeEnum::HONOR)->whereStrictPeriodIn($start_at, $end_at)->get();

        $employees = Employee::with('user', 'contract.position.position')
            ->where('grade_id', userGrades())
            ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
            //   ->whereHas('position', fn($position) => $position->whereIn('position_id', $employee->position->position->children->pluck('id')))
            ->whereHas('position.position', fn($q) => $q->where('type', PositionTypeEnum::GURU->value))
            ->search($request->get('search'))
            ->paginate($request->get('limit', 10));

        return view('finance::summary.teacher.index', compact('start_at', 'end_at', 'departments', 'summaries', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $employee = Employee::findOrFail($request->get('teaching'));
        $userNow = $request->user()->employee->position->position_id;

        $workHour = $employee->getMeta('default_workhour');

        
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

        $scanlogs = $employee->teachingscanlogs()->whereBetween('created_at', [$start_at, $end_at])->groupBy(fn($log) => $log->created_at->format('Y-m-d'));

        $schedules = $employee->schedulesTeachers()->wherePeriodIn($start_at, $end_at)->get()->each(function ($schedule) use ($scanlogs) {
            $schedule->entries = $schedule->getEntryLogs($scanlogs);
            return $schedule;
        });

        $scheduleDuty = $employee->schedulesDutyTeacher()->wherePeriodIn($start_at, $end_at)->get()->each(function ($schedule) {
            return $schedule;
        });

        $entries = $schedules->pluck('entries')->mapWithKeys(fn($k) => $k)
            ->filter(fn($v, $k) => $start_at->lte(Carbon::parse($k)) && $end_at->gte(Carbon::parse($k)));

        $hourReguler = 0;
        $hourExtra = 0;
        $countPresenceExtra = [];

       $total = collect($entries)
        ->map(fn($shifts) => count($shifts))
        ->sum();

        $workDays = $start_at->diffInDaysFiltered(function (Carbon $date) use ($moments) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), ($moments->pluck('date')->toArray()));
        }, $end_at);

        $presences = $entries->flatten(1)->filter(function ($e) {
            return $e->bool === true;
        });


        $adtDays = count($countPresenceExtra);
        $overtime_days = $presences->count() >= $workDays ? $presences->take(($presences->count() - $workDays) * -1) : collect([]);
        $overtime_holidays = $entries->flatten()->whereIn('date', $moments->pluck('date'))->values();

        return view('finance::summary.teacher.create', compact('employee', 'start_at', 'end_at', 'locations', 'leaves', 'vacations', 'overtimes', 'moments', 'schedules', 'entries', 'overtime_days', 'overtime_holidays', 'adtDays', 'workDays', 'presences', 'scanlogs', 'total', 'userNow', 'scheduleDuty'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Employee $employee, StoreRequest $request)
    {
        
        $summaryTypeAtten = array_merge(
            $request->transformed()->toArray(),
            [
                'type' => DataRecapitulationTypeEnum::ATTENDANCE,
                'empl_id' => $request->employee,
            ]
        );

        $summaryTypeHonor = array_merge(
            $request->transformed()->toArray(),
            [
                'type'    => DataRecapitulationTypeEnum::HONOR,
                'empl_id' => $request->employee,
                'result' => [
                    'amount_total' => $request->transformed()->toArray()['result']['amount_total']
                ]
            ]
        );

         $summaryTypeDuty = array_merge(
            $request->transformed()->toArray(),
            [
                'type' => DataRecapitulationTypeEnum::TEACHERDUTY,
                'empl_id' => $request->employee,
                'result' => [
                    'amount_total' => $request->transformed()->toArray()['result']['duty']
                ]
            ]
        );

        $emp = $employee::find($request->employee);

      
        $insertType1 = EmployeeDataRecapitulation::updateOrCreate(
            Arr::only($summaryTypeAtten, ['empl_id', 'type', 'start_at', 'end_at']),
            $summaryTypeAtten
        );

        $insertType8 = EmployeeDataRecapitulation::updateOrCreate(
            Arr::only($summaryTypeHonor, ['empl_id', 'type', 'start_at', 'end_at']),
            $summaryTypeHonor
        );

        if($request->transformed()->toArray()['result']['duty'] > 0){
            $insertType17 = EmployeeDataRecapitulation::updateOrCreate(
                Arr::only($summaryTypeDuty, ['empl_id', 'type', 'start_at', 'end_at']),
                $summaryTypeDuty
            );
        }

        if ($insertType1 && $insertType8) {
            $request->user()->log(
                'melakukan rekapitulasi presensi <strong>' . $emp->user->name . '</strong>',
                Employee::class,
                $request->employee
            );

            return redirect()->next()->with('success', 'Rekapitulasi presensi <strong>' . $emp->user->name . '</strong> berhasil disimpan.');
        }

        return redirect()->fail();
    }

    /* *
     * edit recaps
     */
    public function show($teaching, Request $request)
    {

        $userNow = $request->user()->employee->position;
        $tch = EmployeeRecapSubmission::with('employee')->where(['empl_id' => $teaching, 'start_at' => $request->start_at, 'end_at' => $request->end_at]);
      
        $teachings = (clone $tch)->whereIn('type', [1, 8])->get();
        $tt = (clone $tch)->get()
        ->mapWithKeys(function ($item) {
            return [
                $item->type->value => [
                    'id' => $item->id,
                    'result' => $item->result,
                ]
            ];
        });
        $addition = (clone $tch)
        ->whereNotIn('type', [1, 8])
        ->get()
        ->mapWithKeys(function ($item) {
            return [
                $item->type->value => [
                    'id' => $item->id,
                    'result' => $item->result,
                ]
            ];
        });
    

        $results = ApprovableResultEnum::cases();

        // return $attendance;
        $employee = Employee::findOrFail($teaching);

        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        foreach (WorkLocationEnum::cases() as $location) {
            $locations[$location->value] = $location->name;
        }


        $scanlogs = $employee->teachingscanlogs()->whereBetween('created_at', [$request->start_at, $request->end_at])->groupBy(fn($log) => $log->created_at->format('Y-m-d'));
        $schedules = $employee->schedulesTeachers()->wherePeriodIn($request->start_at, $request->end_at)->get()->each(function ($schedule) use ($scanlogs) {
            $schedule->entries = $schedule->getEntryLogs($scanlogs);
            return $schedule;
        });

        $entries = $schedules->pluck('entries')
        ->mapWithKeys(fn($entries) => $entries)
        ->filter(fn($v, $k) => $start_at->lte(Carbon::parse($k)) && $end_at->gte(Carbon::parse($k)))
        ->map(function ($items) {
            return collect($items)->filter(fn($item) => $item->lesson == '1')->values();
        })
        ->filter(fn($items) => $items->isNotEmpty());

        $presences = $entries->flatten(1)->unique('date');

        return view('finance::summary.teacher.edit', [
            'attendance' => $teachings[0],
            'dtattd' => $tt[1],
            'teach' => $tt[8],
            'addition' => $addition,
            'entries' => $entries,
            'locations' => $locations,
            'scanlogs' => $scanlogs,
            'userNow' => $userNow,
            'moments' => CompanyMoment::holiday()->whereBetween('date', [$request->start_at, $request->end_at])->get(),
            'results' => $results,
            'presences' => $presences
        ]);
    }

    public function submissionApprovals(SubmissionUpdateRequest $request)
    {
        $idAttendance = $request->input('id_attendance');
        $idTeaching = $request->input('id_teaching');
        $idPat = $request->input('id_pat');
        $idTeacherDuty = $request->input('id_teacherduty');
        $idUkm = $request->input('id_ukm');
        $idInvigilator = $request->input('id_teacherinvigilator'); 

        $attendance = EmployeeRecapSubmission::with('approvables')
            ->whereIn('id', [$idAttendance, $idTeaching, $idPat, $idTeacherDuty, $idUkm, $idInvigilator])
            ->get();

        $approvables = $attendance->flatMap->approvables;

        if ($approvables->isEmpty()) {
            return back()->with('error', 'Data tidak ditemukan.');
        }


        foreach ($approvables as $approvable) {
            if (ApprovableResultEnum::APPROVE->value == (int) $request->result) {
                $approvable->update([
                    'result' => ApprovableResultEnum::APPROVE->value,
                    'reason' => $request->input('reason') ?? 'Diterima oleh sistem.',
                ]);

                $submission = EmployeeRecapSubmission::find($approvable->modelable_id);
                $submission->update(['validated_at' => now()]);

                if ((int) $request->result == ApprovableResultEnum::APPROVE->value && $submission) {
                    EmployeeDataRecapitulation::create([
                        'empl_id' => $submission->empl_id,
                        'type' => $submission->type,
                        'start_at' => $submission->start_at,
                        'end_at' => $submission->end_at,
                        'result' => $submission->result,
                    ]);
                }
            } else if (ApprovableResultEnum::REJECT->value == (int) $request->result) {
                $approvable->update([
                    'result' => ApprovableResultEnum::REJECT->value,
                    'reason' => $request->input('reason') ?? 'Ditolak oleh sistem.',
                ]);
            } else if (ApprovableResultEnum::REVISION->value == (int) $request->result) {
                $approvable->update([
                    'result' => ApprovableResultEnum::REVISION->value,
                    'reason' => $request->input('reason') ?? 'Ditolak oleh sistem.',
                ]);
            } else {
                $approvable->update([
                    'result' => $request->input('result'),
                    'reason' => $request->input('reason'),
                ]);
            }
        }

        return back()->with('success', 'Approval berhasil diperbarui.');
    }


    public function update(EmployeeDataRecapitulation $attendance, Employee $employee, UpdateRequest $request)
    {
        if ($employee) {
            dd($employee);
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
