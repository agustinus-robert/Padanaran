<?php

namespace Modules\HRMS\Http\Controllers\Summary;

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
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Http\Requests\Summary\Attendance\StoreRequest;
use Modules\HRMS\Http\Requests\Summary\Attendance\UpdateRequest;
use Modules\HRMS\Models\EmployeeRecapSubmission;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Enums\VacationTypeEnum;
use Modules\Core\Models\CompanyApprovable;
use Modules\HRMS\Http\Requests\Service\Teacher\SubmissionUpdateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class TeachingController extends Controller
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

        $departmentsQuery = CompanyDepartment::where('grade_id', userGrades())->whereIn(
            'id',
            CompanyPosition::whereType(PositionTypeEnum::GURU)
                ->pluck('dept_id')->unique()->toArray()
        )->visible();

        // if ($request->user()->employee->position->position_id == '12' || $request->user()->employee->position->position_id == '7') {
        //     $departments = $departmentsQuery->with(['positions' => fn($poss) => $poss->whereType(PositionTypeEnum::TEACHERjAKARTA)])->get();
        // } else {
            $departments = $departmentsQuery->with(['positions' => fn($poss) => $poss->whereType(PositionTypeEnum::GURU)])->get();
        //}

        $summaries = EmployeeRecapSubmission::whereHas('employee', function($query){
            $query->where('grade_id', userGrades());
        })->whereType(DataRecapitulationTypeEnum::HONOR)->whereStrictPeriodIn($start_at, $end_at)->get();
        $employeesQuery = Employee::where('grade_id', userGrades())->with('user', 'contract.position.position')
            ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
            ->whereHas('position', fn($position) => $position->whereIn('position_id', $employee->position->position->children->pluck('id')));

        // if ($request->user()->employee->position->position_id == '12' || $request->user()->employee->position->position_id == '7') {
        //     $employeesQuery->whereHas('position.position', fn($q) => $q->where('type', PositionTypeEnum::GURU->value));
        // } else {
        $employeesQuery->whereHas('position.position', fn($q) => $q->where('type', PositionTypeEnum::GURU->value));
        //}



        $search = $request->get('search');
        if ($search) {
            $employeesQuery->where(function ($query) use ($search) {
                $query->search($search)
                    ->orWhereMetaLike('code', $search);
            });
        }

        $employees = $employeesQuery->paginate($request->get('limit', 10));


        return view('hrms::summary.teacher.index', compact('start_at', 'end_at', 'departments', 'summaries', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $employee = Employee::where('grade_id', userGrades())->findOrFail($request->get('employee'));
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
        $momentDates = $moments->pluck('date')->toArray();
        $scanlogs = $employee->teachingscanlogs()->whereBetween('created_at', [$start_at, $end_at])->groupBy(fn($log) => $log->created_at->format('Y-m-d'));

        // bukan per jadwal shift namun kehadiran menyesuaikan di absens
        $schedules = $employee->schedulesTeachers()->wherePeriodIn($start_at, $end_at)->get()->each(function ($schedule) use ($scanlogs) {
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

        // kalau untuk perhitungan dikunci ke 2 jam (mis: 08:00 - 10:00)

        $employee = Employee::with('user', 'contract', 'schedulesTeachers', 'vacations', 'leaves')
        ->where('grade_id', userGrades())
        ->findOrFail($request->get('employee'));

        $employeeVacations = $employee->vacations()->with('quota.category')->get();
        $employeeLeaves = $employee->leaves;

        $vacationSums = [];
        $dateVacations = [];
        $dateLeaves = [];
        foreach ($employeeVacations as $value) {
            foreach (json_decode($value->dates, true) as $val) {
                $dateVacations[$val['d']] = 1.5;
            }
        }

        foreach ($employeeLeaves as $value) {
            foreach (json_decode($value->dates, true) as $val) {
                $dateLeaves[$val['d']] = 1.5;
            }
        }

        foreach (VacationTypeEnum::cases() as $type) {
            $total = $employeeVacations
                ->filter(fn($vac) => $vac->quota?->category?->type->value === $type->value)
                ->sum(function ($vac) {
                    $dates = json_decode($vac->dates, true);
                    return is_array($dates) ? count($dates) : 0;
                });

            $vacationSums[strtolower($type->name)] = $total;
        }




        $hourReguler = 0;
        $hourExtra = 0;
        $countPresenceExtra = [];
        $currentDate = null;

        foreach ($entries as $hours => $hour) {
            foreach ($hour as $shifts => $shift) {
                if(!empty($shift->lesson)){
                    $in = Carbon::parse($shift->schedule[0]->toTimeString());
                    $defaultStart = Carbon::createFromTime(8, 0, 0);

                    // Cek apakah ada modifier
                    $modifier = $shift->modifier ?? null;
                    $adjustment = 0; // default adjustment

                    if ($modifier !== null) {
                        if (str_starts_with($modifier, '+')) {
                            $adjustment = floatval($modifier); // ex: +0.5 → 0.5
                        } elseif (str_starts_with($modifier, '-')) {
                            $adjustment = floatval($modifier); // ex: -1 → -1
                        }
                    }

                    $isVacatonOrSick = isset($dateVacations[$shift->date]) || isset($dateLeaves[$shift->date]);

                    if ($currentDate !== $shift->date) {
                        $currentDate = $shift->date;

                        if ($isVacatonOrSick) {
                            $hourReguler += 1.5;
                            continue;
                        }
                    }

                    $dateWeekEnd = date('w', strtotime($shift->date));

                    if($dateWeekEnd == 0 || $dateWeekEnd == 6){
                        $hourExtra += (2 + $adjustment);
                        $countPresenceExtra[] = $in;
                    } else {
                        // if ($shift->shift->value < 5) {
                        //     // Default: semua shift reguler dihitung 2 jam
                            $baseHour = 2 + $adjustment;
                            $hourReguler += $baseHour;

                        //     // Jika shift 1 dan mulai sebelum jam 08:00, maka selisih masuk ke extra
                        //     if ($shift->shift->value == 1 && $in->lessThan($defaultStart)) {
                        //         $extraMinutes = $in->diffInMinutes($defaultStart);
                        //         $extraHours = $extraMinutes / 60;

                        //         $hourExtra += $extraHours;
                        //         $hourReguler -= $extraHours; // koreksi jam reguler
                        //     }
                        // } elseif ($shift->shift->value == 5) {
                        //     // Shift ke-5 langsung masuk ke jam ekstra, tetap 2 jam
                        //     $hourExtra += (2 + $adjustment);
                        //     $countPresenceExtra[] = $in;
                        // }
                    }
                }
            }
        }


        $workDays = $start_at->diffInDaysFiltered(function (Carbon $date) use ($moments) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), ($moments->pluck('date')->toArray()));
        }, $end_at);

        $day = Carbon::today();
        $thisDay = Carbon::today()->toDateString();

        $presences = $entries->flatten(1)->unique('date');

        $matchedData = [];
        $adtWfh = 0;
        $adtWfo = 0;


        $momentDates = $moments->pluck('date')->toArray();

        if ($employee->contract()->first()?->work_location->value == 1) {
            foreach ($scanlogs as $date => $logs) {
                $entriesForDate = $entries[$date] ?? [];
                $matchedForDate = false;

                if (in_array($date, $momentDates)) {
                    $matchedForDate = true;
                } else {
                    foreach ($logs as $log) {
                        $scanTime = Carbon::parse($log->created_at);
                        $toleranceStart = Carbon::parse($scanTime->format('Y-m-d') . ' 17:00:00');
                        $toleranceEnd = Carbon::parse($scanTime->format('Y-m-d') . ' 19:00:00');

                        $matched = false;

                        if (!empty($entriesForDate)) {
                            $matched = collect($entriesForDate)->first(function ($entry) use ($scanTime, $toleranceStart, $toleranceEnd) {
                                [$start, $end] = $entry->schedule;

                                return ($start && $end && $scanTime->between($start, $end)) ||
                                    $scanTime->between($toleranceStart, $toleranceEnd);
                            });
                        }

                        if (empty($entriesForDate) && $scanTime->between($toleranceStart, $toleranceEnd)) {
                            $matched = true;
                        }

                        if ($matched) {
                            $matchedForDate = true;
                            break;
                        }
                    }
                }

                if ($matchedForDate) {
                    $adtWfh++;
                }
            }
        } else if ($employee->contract()->first()?->work_location->value == 2) {
            foreach ($scanlogs as $date => $logs) {
                if (in_array($date, $momentDates)) {
                    foreach ($logs as $log) {
                        if ($log->location == 1) {
                            $adtWfo++;
                        }
                    }
                }

                elseif (!empty($logs)) {
                    foreach ($logs as $log) {
                        if ($log->location == 1) {
                            $adtWfo++;
                            break;
                        }
                    }
                }
            }
        }

        // dd($entries->flatten(1));

        //bukan kehadiran per shift
        //->filter(function ($e) {
        //  return $e->bool === true;
        // });

        //bukan kehadiran per shift

        if ($scanlogs) {
            $presenced = collect($entries)
                ->only($scanlogs->keys())
                ->map(function ($entry, $date) use ($scanlogs) {
                    $logs = $scanlogs[$date];
                    $firstLog = $logs->first();

                    return (object)[
                        'location' => $firstLog?->location,
                        'entry' => collect($entry),
                    ];
                });
        }

        $presenced->unique('date');
        $presencesTotal = $presences->unique('date');


        $adtDays = count($countPresenceExtra);
        $overtime_days = $presences->count() >= $workDays ? $presences->take(($presences->count() - $workDays) * -1) : collect([]);
        $overtime_holidays = $entries->flatten()->whereIn('date', $moments->pluck('date'))->values();

        /*
            rumus perhitungan untuk pengajar atau guru
        */

        /*
            RUMUS UNTUK CEK JIKA BEBAN MENGAJAR KURANG DARI JAM MINIMAL PENGAJARAN
            cek dahulu apakah $hourTotal bisa melebihi beban mengajar, dimana rumusnya adalah
            jam reguler + jam extra
            jika kurang dari beban mengajar tambahkan jam reguler dan extra sampai mencukupi beban mengajar
        */
        $extraOver = 0;
        $hourTotal = $hourExtra + $hourReguler;

        //tambahkan 1 rumus lagi 05/29/2025, jika kurang dari hour reguler, maka tetap tampilkan hour extra * 35%
        // if($hourReguler < $workHour){
        //     $hourExtra = $hourExtra;
        //     $hourRegules = $hourReguler;
        // } else if ($hourReguler > $workHour) {
        //     /*
        //         jika dari shift 1-4 tesebut melebihi beban mengajar

        //         maka didapatkan kelebihan mengajar
        //     */

        //     $extraOver = $hourReguler - $workHour;
        //     $hourTotal = $workHour;
        // } else if ($hourTotal > $workHour) {
        //     /*
        //         setelah ditambahkan dari shift 1-4 + extra mengajar maka akan menghasilkan
        //         KELEBIHAN MENGAJAR EXTRA
        //     */
        //     $hourExtra = $hourTotal - $workHour;
        //     $hourTotal = $workHour;
        // } else if ($hourTotal < $workHour) {
        //     $hourExtra = 0;
        // }

        // $employeeVacationsSums = $vacationSums;

        /*
            akhir rumus perhitungan
        */

        // $employeeVacationsSums = 0;
        return view('hrms::summary.teacher.create', compact('employee', 'start_at', 'end_at', 'locations', 'leaves', 'vacations', 'overtimes', 'moments', 'schedules', 'entries', 'overtime_days', 'overtime_holidays', 'adtDays', 'workDays', 'presences', 'adtWfo', 'scanlogs', 'extraOver', 'hourExtra', 'workHour', 'hourReguler', 'hourTotal', 'userNow', 'presencesTotal', 'adtWfh', 'presenced', 'employeeLeaves', 'employeeVacations'));
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


        $summaryTypeHonor['result'] = [];
        $summaryTypeHonor['result'] = ['amount_total' => $summaryTypeAtten['result']['resultHour']];

        // $summaryTypeHonor['result']['amount_real'] = $summaryTypeAtten['result']['result_hour'];
        // unset($summaryTypeAtten['result']['result_hour']);

        $summaryBase = [
            'start_at' => $summaryTypeAtten['start_at'],
            'end_at' => $summaryTypeAtten['end_at'],
            'empl_id' => $summaryTypeAtten['empl_id'],
        ];

        // Setiap jenis summary perlu diisi lengkap
        $summaryTypeHonor = array_merge($summaryBase, [
            'type' => DataRecapitulationTypeEnum::HONOR,
            'result' => [
                'amount_total' => $summaryTypeAtten['result']['resultHour'] ?? 0,
            ],
        ]);

        $summaryTypePat = array_merge($summaryBase, [
            'type' => DataRecapitulationTypeEnum::PAT,
            'result' => [
                'amount_total' => $summaryTypeAtten['result']['duty'] ?? 0,
            ],
        ]);

        $summaryTypeDuty = array_merge($summaryBase, [
            'type' => DataRecapitulationTypeEnum::TEACHERDUTY,
            'result' => [
                'amount_total' => $summaryTypeAtten['result']['pat'] ?? 0,
            ],
        ]);

        $summaryTypeUKM = array_merge($summaryBase, [
            'type' => DataRecapitulationTypeEnum::UKM,
            'result' => [
                'amount_total' => $summaryTypeAtten['result']['ukm'] ?? 0,
            ],
        ]);

        $summaryTypeInvigilator = array_merge($summaryBase, [
            'type' => DataRecapitulationTypeEnum::TEACHERINVIGILATOR,
            'result' => [
                'amount_total' => $summaryTypeAtten['result']['duty'] ?? 0,
            ],
        ]);

        unset($summaryTypeAtten['result']['duty']);
        unset($summaryTypeAtten['result']['pat']);
        unset($summaryTypeAtten['result']['ukm']);
        unset($summaryTypeAtten['result']['invigilator']);

        $emp = $employee::find($request->employee);

        if ($request->user()->employee->position->position_id !== 9) {
            $summaryTypes = [
                $summaryTypeAtten,
                $summaryTypeHonor,
                $summaryTypePat,
                $summaryTypeDuty,
                $summaryTypeUKM,
                $summaryTypeInvigilator,
            ];

        $inserted = [];

            foreach ($summaryTypes as $summary) {
                if (
                    isset($summary['empl_id'], $summary['type'], $summary['start_at'], $summary['end_at']) &&
                    !is_null($summary['empl_id']) && !is_null($summary['type'])
                ) {
                    $inserted[] = EmployeeRecapSubmission::updateOrCreate(
                        Arr::only($summary, ['empl_id', 'type', 'start_at', 'end_at']),
                        $summary
                    );
                } else {
                    \Log::warning('Data summary tidak lengkap', $summary);
                }
            }

            foreach (config('modules.core.features.services.recapteacher.approvable_steps', []) as $model) {
                if ($model['type'] === 'employee_position_by_kd') {
                    $approver = EmployeePosition::active()
                        ->whereHas('position', fn($query) => $query->whereIn('kd', $model['value']))
                        ->first();

                    if ($approver) {
                        foreach ($inserted as $submission) {
                            $submission->createApprovable($approver);
                        }
                    }
                }
            }
        } else {
            $insertType1 = EmployeeDataRecapitulation::updateOrCreate(
                Arr::only($summaryTypeAtten, ['empl_id', 'type', 'start_at', 'end_at']),
                $summaryTypeAtten
            );

            $insertType8 = EmployeeDataRecapitulation::updateOrCreate(
                Arr::only($summaryTypeHonor, ['empl_id', 'type', 'start_at', 'end_at']),
                $summaryTypeHonor
            );

            $insertType16 = EmployeeRecapSubmission::updateOrCreate(
                Arr::only($summaryTypePat, ['empl_id', 'type', 'start_at', 'end_at']),
                $summaryTypePat
            );

            $insertType17 = EmployeeRecapSubmission::updateOrCreate(
                Arr::only($summaryTypeDuty, ['empl_id', 'type', 'start_at', 'end_at']),
                $summaryTypeDuty
            );

            $insertType18 = EmployeeRecapSubmission::updateOrCreate(
                Arr::only($summaryTypeUKM, ['empl_id', 'type', 'start_at', 'end_at']),
                $summaryTypeUKM
            );

            $insertType19 = EmployeeRecapSubmission::updateOrCreate(
                Arr::only($summaryTypeInvigilator, ['empl_id', 'type', 'start_at', 'end_at']),
                $summaryTypeInvigilator
            );
        }


        if (count($summaryTypes) > 0) {

            // $signs = array_map(function ($sign) use ($insertType1) {
            //     if ($sign == '%SELECTED_EMPLOYEE_USER_ID%') {
            //         return $insertType1->employee->user_id;
            //     } elseif (isset($sign['model'])) {
            //         $model = new $sign['model']();
            //         foreach ($sign['methods'] as $method) {
            //             $model = $model->{$method['w']}(...$method['p']);
            //         }
            //         return data_get($model, $sign['get']);
            //     }
            // }, config('modules.core.features.services.reacap_teacher.documentable_signs'));
            if ($request->user()->employee->position->position_id !== 9) {
                return redirect()->next()->with('success', 'Rekapitulasi presensi <strong>' . $emp->user->name . '</strong> sedang diajukan.');
            } else {
                $request->user()->log(
                    'melakukan rekapitulasi presensi <strong>' . $emp->user->name . '</strong>',
                    Employee::class,
                    $request->employee
                );

                return redirect()->next()->with('success', 'Rekapitulasi presensi <strong>' . $emp->user->name . '</strong> berhasil disimpan.');
            }
        }

        return redirect()->fail();
    }

    /* *
     * edit recaps
     */
    public function show($teaching, Request $request)
    {

        $userNow = $request->user()->employee->position;
        $teachings = EmployeeRecapSubmission::where(['empl_id' => $teaching, 'start_at' => $request->start_at, 'end_at' => $request->end_at])->get();

        // $employee = Employee::findOrFail($request->get('employee'));
        // $userNow = $request->user()->employee->position->position_id;


        $filteredRecap = $teachings->filter(fn($item) => in_array($item->type, [DataRecapitulationTypeEnum::ATTENDANCE, DataRecapitulationTypeEnum::HONOR]))->values();

        $results = ApprovableResultEnum::cases();

        // return $attendance;
        $employee = Employee::with('user','contract', 'schedulesTeachers','vacations','leaves')->findOrFail($teaching);
        $workHour = $employee->getMeta('default_workhour');

        $employeeVacations = $employee->vacations()->with('quota.category')->get();
        $employeeLeaves = $employee->leaves;

        $vacationSums = [];
        $dateVacations = [];
        $dateLeaves = [];
        foreach($employeeVacations as $value){
            foreach(json_decode($value->dates, true) as $val){
                $dateVacations[$val['d']] = 1.5;
            }
        }

        foreach($employeeLeaves as $value){
            foreach (json_decode($value->dates, true) as $val) {
                $dateLeaves[$val['d']] = 1.5;
            }
        }

        foreach (VacationTypeEnum::cases() as $type) {
            $total = $employeeVacations
                ->filter(fn($vac) => $vac->quota?->category?->type->value === $type->value)
                ->sum(function ($vac) {
                    $dates = json_decode($vac->dates, true);
                    return is_array($dates) ? count($dates) : 0;
                });

            $vacationSums[strtolower($type->name)] = $total;
        }


        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        $moments = CompanyMoment::holiday()->whereBetween('date', [$start_at, $end_at])->get();
        $momentDates = $moments->pluck('date')->toArray();

        foreach (WorkLocationEnum::cases() as $location) {
            $locations[$location->value] = $location->name;
        }


        $scanlogs = $employee->teachingscanlogs()->whereBetween('created_at', [$request->start_at, $request->end_at])->groupBy(fn($log) => $log->created_at->format('Y-m-d'));
        $schedules = $employee->schedulesTeachers()->wherePeriodIn($request->start_at, $request->end_at)->get()->each(function ($schedule) use ($scanlogs) {
            $schedule->entries = $schedule->getEntryLogs($scanlogs);
            return $schedule;
        });

        $hourReguler = 0;
        $hourExtra = 0;
        $adtWfh = 0;
        $adtWfo = 0;
        $countPresenceExtra = [];

        $entries = $schedules->pluck('entries')->mapWithKeys(fn($k) => $k)
            ->filter(fn($v, $k) => $start_at->lte(Carbon::parse($k)) && $end_at->gte(Carbon::parse($k)));

        $currentDate = null;

        foreach ($entries as $hours => $hour) {
            foreach ($hour as $shifts => $shift) {
                $in = Carbon::parse($shift->schedule[0]->toTimeString());
                $defaultStart = Carbon::createFromTime(8, 0, 0);

                $modifier = $shift->modifier ?? null;
                $adjustment = 0;

                if ($modifier !== null) {
                    if (str_starts_with($modifier, '+') || str_starts_with($modifier, '-')) {
                        $adjustment = floatval($modifier);
                    }
                }

                $dateWeekEnd = date('w', strtotime($shift->date));
                $isVacatonOrSick = isset($dateVacations[$shift->date]) || isset($dateLeaves[$shift->date]);

                if ($currentDate !== $shift->date) {
                    $currentDate = $shift->date;

                    if ($isVacatonOrSick) {
                        $hourReguler += 1.5;
                        continue;
                    }
                }

                if ($dateWeekEnd == 0 || $dateWeekEnd == 6) {
                    $hourExtra += (2 + $adjustment);
                    $countPresenceExtra[] = $in;
                } else {
                    if ($shift->shift->value < 5) {
                        $baseHour = 2 + $adjustment;
                        $hourReguler += $baseHour;

                        if ($shift->shift->value == 1 && $in->lessThan($defaultStart)) {
                            $extraMinutes = $in->diffInMinutes($defaultStart);
                            $extraHours = $extraMinutes / 60;

                            $hourExtra += $extraHours;
                            $hourReguler -= $extraHours;
                        }
                    } elseif ($shift->shift->value == 5) {
                        $hourExtra += (2 + $adjustment);
                        $countPresenceExtra[] = $in;
                    }
                }
            }
        }



        if ($employee->contract()->first()?->work_location->value == 1) {
            foreach ($scanlogs as $date => $logs) {
                $entriesForDate = $entries[$date] ?? [];
                $matchedForDate = false;

                if (in_array($date, $momentDates)) {
                    $matchedForDate = true;
                } else {
                    foreach ($logs as $log) {
                        $scanTime = Carbon::parse($log->created_at);
                        $toleranceStart = Carbon::parse($scanTime->format('Y-m-d') . ' 17:00:00');
                        $toleranceEnd = Carbon::parse($scanTime->format('Y-m-d') . ' 19:00:00');

                        $matched = false;

                        if (!empty($entriesForDate)) {
                            $matched = collect($entriesForDate)->first(function ($entry) use ($scanTime, $toleranceStart, $toleranceEnd) {
                                [$start, $end] = $entry->schedule;

                                return ($start && $end && $scanTime->between($start, $end)) ||
                                    $scanTime->between($toleranceStart, $toleranceEnd);
                            });
                        }

                        if (empty($entriesForDate) && $scanTime->between($toleranceStart, $toleranceEnd)) {
                            $matched = true;
                        }

                        if ($matched) {
                            $matchedForDate = true;
                            break;
                        }
                    }
                }

                if ($matchedForDate) {
                    $adtWfh++;
                }
            }
        } else if ($employee->contract()->first()?->work_location->value == 2) {
            foreach ($scanlogs as $date => $logs) {
                if (in_array($date, $momentDates)) {
                    foreach ($logs as $log) {
                        if ($log->location == 1) {
                            $adtWfo++;
                        }
                    }
                } elseif (!empty($logs)) {
                    foreach ($logs as $log) {
                        if ($log->location == 1) {
                            $adtWfo++;
                            break;
                        }
                    }
                }
            }
        }

        if ($scanlogs) {
            $presenced = collect($entries)
                ->only($scanlogs->keys())
                ->map(function ($entry, $date) use ($scanlogs) {
                    $logs = $scanlogs[$date];
                    $firstLog = $logs->first();

                    return (object)[
                        'location' => $firstLog?->location,
                        'entry' => collect($entry),
                    ];
                });
        }

        $presenced->unique('date');
        $extraOver = 0;
        $hourTotal = $hourExtra + $hourReguler;

        if ($hourReguler < $workHour) {
            $hourExtra = $hourExtra;
            $hourTotal = $hourReguler;
        } else if ($hourReguler > $workHour) {
            /*
                jika dari shift 1-4 tesebut melebihi beban mengajar

                maka didapatkan kelebihan mengajar
            */

            $extraOver = $hourReguler - $workHour;
            $hourTotal = $workHour;
        } else if ($hourTotal > $workHour) {
            /*
                setelah ditambahkan dari shift 1-4 + extra mengajar maka akan menghasilkan
                KELEBIHAN MENGAJAR EXTRA
            */
            $hourExtra = $hourTotal - $workHour;
            $hourTotal = $workHour;
        } else if ($hourTotal < $workHour) {
            $hourExtra = 0;
        }

        return view('administration::summary.teacher.edit', [
            'attendance' => $filteredRecap[0],
            'teach' => $filteredRecap[1],
            'entries' => $entries,
            'locations' => $locations,
            'scanlogs' => $scanlogs,
            'userNow' => $userNow,
            'moments' => CompanyMoment::holiday()->whereBetween('date', [$request->start_at, $request->end_at])->get(),
            'results' => $results,
            'hourTotal' => $hourTotal,
            'hourExtra' => $hourExtra,
            'extraOver' => $extraOver,
            'workHour' => $workHour,
            'hourReguler' => $hourReguler,
            'employee' => $employee,
            'presenced' => $presenced,
            'adtWfo' => $adtWfo,
            'adtWfh' => $adtWfh,
            'employeeLeaves' => $employeeLeaves,
            'employeeVacationsSums' => $vacationSums,
            'employeeVacations' => $employeeVacations
        ]);
    }

    public function submissionApprovals(SubmissionUpdateRequest $request)
    {
        $idAttendance = $request->input('id_attendance');
        $idTeaching = $request->input('id_teaching');

        $attendance = EmployeeRecapSubmission::with('approvables')
            ->whereIn('id', [$idAttendance, $idTeaching])
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
                        'empl_id' => $approvable->userable_id,
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


    public function update(Employee $teaching, UpdateRequest $request)
    {
        $summaryTypeAtten = array_merge(
            $request->transformed()->toArray(),
            [
                'type' => DataRecapitulationTypeEnum::ATTENDANCE,
                'empl_id' => $teaching->id,
            ]
        );

        unset($summaryTypeAtten['resultHour']);
        $summaryTypeHonor = array_merge(
            $request->transformed()->toArray(),
            [
                'type' => DataRecapitulationTypeEnum::HONOR,
                'empl_id' => $teaching->id,
            ]
        );

        $summaryTypeHonor['result'] = [];
        $summaryTypeHonor['result'] = $summaryTypeHonor['resultHour'];
        $summaryTypeHonor = array_merge($summaryTypeHonor, $summaryTypeHonor['result']);
        //$teaching->getMeta('default_workhour')
        if($summaryTypeHonor['resultHour']['amount_total'] < $teaching->getMeta('default_workhour')){
            $summaryTypeHonor['result']['amount_total'] = $teaching->getMeta('default_workhour');
            $summaryTypeHonor['result']['extrahour'] = $summaryTypeHonor['result']['extrahour'] * 0.35;
        }

        $summaryTypeHonor['result']['amount_real'] = $summaryTypeHonor['resultHour']['amount_total'];


        unset($summaryTypeHonor['resultHour']);
        $emp = Employee::find($teaching->id);

        $insertType1 = EmployeeRecapSubmission::updateOrCreate(
            Arr::only($summaryTypeAtten, ['empl_id', 'type', 'start_at', 'end_at']),
            $summaryTypeAtten
        );

        $insertType8 = EmployeeRecapSubmission::updateOrCreate(
            Arr::only($summaryTypeHonor, ['empl_id', 'type', 'start_at', 'end_at']),
            $summaryTypeHonor
        );



        if ($insertType1 && $insertType8) {

            // $signs = array_map(function ($sign) use ($insertType1) {
            //     if ($sign == '%SELECTED_EMPLOYEE_USER_ID%') {
            //         return $insertType1->employee->user_id;
            //     } elseif (isset($sign['model'])) {
            //         $model = new $sign['model']();
            //         foreach ($sign['methods'] as $method) {
            //             $model = $model->{$method['w']}(...$method['p']);
            //         }
            //         return data_get($model, $sign['get']);
            //     }
            // }, config('modules.core.features.services.reacap_teacher.documentable_signs'));

            return redirect()->next()->with('success', 'Rekapitulasi pengajaran <strong>' . $emp->user->name . '</strong> berhasil diperbarui.');
        }

        return redirect()->fail();
    }
}
