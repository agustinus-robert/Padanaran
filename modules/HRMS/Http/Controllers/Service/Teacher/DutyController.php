<?php

namespace Modules\HRMS\Http\Controllers\Service\Teacher;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\HRMS\Enums\ObShiftEnum;
use Modules\HRMS\Enums\TeacherShiftEnum;
use Modules\HRMS\Enums\DutyShiftTeacher;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeScheduleTeacher;
use Modules\HRMS\Models\EmployeeTeacherDuty;
use Modules\HRMS\Models\EmployeeScheduleShiftDuty;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;
use Modules\Administration\Models\SchoolBuildingRoom;
use Modules\HRMS\Repositories\EmployeeCollectiveScheduleTeacherRepository;
use Modules\HRMS\Repositories\EmployeeRepository;

use Modules\Portal\Http\Controllers\Controller;
use Modules\HRMS\Http\Requests\Service\Attendance\CollectiveSubmission\StoreRequest;

class DutyController extends Controller
{
    use EmployeeRepository, EmployeeCollectiveScheduleTeacherRepository;

    public function index(Request $request){
        $this->authorize('access', EmployeeScheduleTeacher::class);

        $user     = $request->user();
        $employee = $user->employee->load('position.position.children');

        $startM = Carbon::parse($request->get('start_at', now()));
        
        $month = $request->filled('month')
        ? Carbon::parse($request->get('month'))
        : $startM->copy();

        $start_at = request('start_at') ?? now()->copy()->subDays(6)->format('Y-m-d');
        $end_at   = request('end_at') ?? now()->copy()->format('Y-m-d');
        $shiftDatabs = EmployeeScheduleShiftDuty::where('status', 1)->get();

        $startDate = request()->filled('start_at')
                ? Carbon::parse(request('start_at'))
                : now()->startOfWeek(Carbon::SUNDAY);

        switch ($request->get('type')) {
            case 'teacher':
                $type = PositionTypeEnum::GURU->value;
                $label = PositionTypeEnum::GURU->key();
                $workshifts = DutyShiftTeacher::cases();
                break;

            default:
                $type = PositionTypeEnum::GURU->value;
                $label = PositionTypeEnum::GURU->key();
                $workshifts = DutyShiftTeacher::cases();
                break;
        }


        $employees = Employee::with([
            'user.meta',
            'position.position',
            'schedulesDutyTeacher' => fn($schedule) => $schedule->whereDate('start_at', '<=', $end_at)->whereDate('end_at', '>=', $start_at),
        ])
            ->whereHas('position', fn($position) => $position->whereIn('position_id', $employee->position->position->children->pluck('id')))
            ->where('grade_id', userGrades())
            ->when($type, fn($t) => $t->whereHas('position.position', fn($q) => $q->where('type', $type)))
            ->search($request->get('search'))
            ->whenTrashed($request->get('trash'))
            ->paginate(10); 


        $empPersonil = [];
        foreach ($employees as $employee) {
            $empPersonil[$employee->id] = $employee->id;
            $employee->color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        }

        $employee_count = $employees->count();

        $calendarData = EmployeeTeacherDuty::whereMonth('start_at', $month)
            ->with(['employee.user', 'employee.position.position'])
            ->whereHas('employee', function ($q) {
                $q->where('grade_id', userGrades()); 
            })
            ->get()
            ->groupBy('empl_id');

        // Array yang akan menampung hasil
        $result = [];

        // Looping data kalender
        foreach ($calendarData as $keyShowData => $valShowData) {
            if (isset($empPersonil[$keyShowData])) {
                $result[$keyShowData] = [];
                foreach ($valShowData as $keyLevel2 => $valLevel2) {
                    $result[$keyShowData][$type] = [];
                    foreach ($valLevel2->dates as $keyLevel3 => $valLevel3) {
                        foreach ($valLevel3 as $keyLevel4 => $valLevel4) {
                            if (is_array($valLevel4) && isset($valLevel4[0]) && $valLevel4[0] != null) {                                
                                $result[$keyShowData][$type][$keyLevel3][] = $keyLevel4 + 1;
                            }
                        }
                    }
                }
            }
        }

        $databaseResult = '';
        if (count($result) > 0) {
            $databaseResult = json_encode($result);
        }
        
        $room = SchoolBuildingRoom::with('building')
        ->whereHas('building', function($q){
            $q->where('grade_id', userGrades());
        })
        ->whereNull('deleted_at')->get();


        return view('hrms::service.schedules_teacher.manage.collective.index', compact('user', 'employees', 'employee_count', 
        'month', 'workshifts', 'calendarData', 'type', 'databaseResult', 'label', 'start_at', 'end_at', 'shiftDatabs', 'startDate',
        'room'));
    }
    /**
     * Display a listing of the resource.
     */
    public function create(Request $request)
    {
        $this->authorize('access', EmployeeScheduleTeacher::class);

        if (empty($request->start_at) && empty($request->end_at)) {
            return redirect()->back()->with('error', 'Periode wajib diisi.');
        }

        $user     = $request->user();
        $employee = $user->employee->load('position.position.children');

        $startM = Carbon::parse($request->get('start_at', now()));
        
        $month = $request->filled('month')
        ? Carbon::parse($request->get('month'))
        : $startM->copy();

        $start_at = request('start_at') ?? now()->copy()->subDays(6)->format('Y-m-d');
        $end_at   = request('end_at') ?? now()->copy()->format('Y-m-d');
        $shiftDatabs = EmployeeScheduleShiftDuty::where('grade_id', userGrades())->where('status', 1)->get();

        $startDate = request()->filled('start_at')
                ? Carbon::parse(request('start_at'))
                : now()->startOfWeek(Carbon::SUNDAY);

        switch ($request->get('type')) {
            case 'teacher':
                $type = PositionTypeEnum::GURU->value;
                $label = PositionTypeEnum::GURU->key();
                $workshifts = DutyShiftTeacher::cases();
                break;

            default:
                $type = PositionTypeEnum::GURU->value;
                $label = PositionTypeEnum::GURU->key();
                $workshifts = DutyShiftTeacher::cases();
                break;
        }


        $employees = Employee::with([
            'user.meta',
            'position.position',
            'schedules' => fn($schedule) => $schedule->whereDate('start_at', '<=', $end_at)->whereDate('end_at', '>=', $start_at),
        ])
            ->where('grade_id', userGrades())
            ->whereHas('position', fn($position) => $position->whereIn('position_id', $employee->position->position->children->pluck('id')))
            ->when($type, fn($t) => $t->whereHas('position.position', fn($q) => $q->where('type', $type)))
            ->search($request->get('search'))->whenTrashed($request->get('trash'))->get();

        $empPersonil = [];
        foreach ($employees as $employee) {
            $empPersonil[$employee->id] = $employee->id;
            $employee->color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        }

        $employee_count = $employees->count();

        $calendarData = EmployeeTeacherDuty::whereMonth('start_at', $month)
            ->with(['employee.user', 'employee.position.position'])
            ->whereHas('employee', function ($q) {
                  $q->where('grade_id', userGrades()); 
            })
            ->get()
            ->groupBy('empl_id');

        // Array yang akan menampung hasil
        $result = [];

        // Looping data kalender
        foreach ($calendarData as $keyShowData => $valShowData) {
            if (isset($empPersonil[$keyShowData])) {
                $result[$keyShowData] = [];
                foreach ($valShowData as $keyLevel2 => $valLevel2) {
                    $result[$keyShowData][$type] = [];
                    foreach ($valLevel2->dates as $keyLevel3 => $valLevel3) {
                        foreach ($valLevel3 as $keyLevel4 => $valLevel4) {
                            if (is_array($valLevel4) && isset($valLevel4[0]) && $valLevel4[0] != null) {                                
                                $result[$keyShowData][$type][$keyLevel3][] = $keyLevel4 + 1;
                            }
                        }
                    }
                }
            }
        }

        $databaseResult = '';
        if (count($result) > 0) {
            $databaseResult = json_encode($result);
        }
        
        $room = SchoolBuildingRoom::with('building')->whereNull('deleted_at')->get();

        return view('hrms::service.schedules_teacher.manage.collective.create', array_merge(compact('user', 'employees', 'employee_count', 
        'month', 'workshifts', 'calendarData', 'type', 'databaseResult', 'label', 'start_at', 'end_at', 'shiftDatabs', 'startDate',
        'room')), $this->loadModal($startM));
    }

    public function loadModal($month){
        // $shiftId = $request->get('shift_id');
        // $date = $request->get('date');
        // $gender = $request->get('gender');

        //  $startM = Carbon::parse($request->get('start_at', now()));
        // $month = $request->filled('month')
        // ? Carbon::parse($request->get('month'))
        // : $startM->copy();

        $calendarData = EmployeeTeacherDuty::whereMonth('start_at', $month)
            ->with(['employee.user', 'employee.position.position'])
            ->whereHas('employee', function ($q) {
              $q->where('grade_id', userGrades()); 
            })
            ->get()
            ->groupBy('empl_id');

        // Array yang akan menampung hasil
        $result = [];

        // Looping data kalender
        foreach ($calendarData as $keyShowData => $valShowData) {
            if (isset($empPersonil[$keyShowData])) {
                $result[$keyShowData] = [];
                foreach ($valShowData as $keyLevel2 => $valLevel2) {
                    $result[$keyShowData][$type] = [];
                    foreach ($valLevel2->dates as $keyLevel3 => $valLevel3) {
                        foreach ($valLevel3 as $keyLevel4 => $valLevel4) {
                            if (is_array($valLevel4) && isset($valLevel4[0]) && $valLevel4[0] != null) {                                
                                $result[$keyShowData][$type][$keyLevel3][] = $keyLevel4 + 1;
                            }
                        }
                    }
                }
            }
        }

        $databaseResult = '';
        if (count($result) > 0) {
            $databaseResult = json_encode($result);
        }

        $shiftDatabs = EmployeeScheduleShiftDuty::whereStatus(1)->get();
        $type = PositionTypeEnum::GURU->value;
        $employees = Employee::where('grade_id', userGrades())
        ->whereHas('position.position', function ($query) use ($type) {
            $query->where('type', $type);
        })->get();

        $room = SchoolBuildingRoom::with('building')->whereNull('deleted_at')->get();

//        return view('hrms::service.schedules_teacher.manage.collective.modal', compact('databaseResult', 'shiftId', 'date', 'shiftDatabs', 'employees', 'room', 'gender', 'month'));
        return compact('databaseResult', 'shiftDatabs', 'employees', 'room', 'month');

    }

    public function show(Employee $duty, Request $request) {
        $start_at = $request->start_at
            ? Carbon::parse($request->start_at)->format('Y-m-d')
            : now()->format('Y-m-d');

        $end_at = $request->end_at
            ? Carbon::parse($request->end_at)->format('Y-m-d')
            : now()->format('Y-m-d');

        $duty->load(['user.meta', 'position.position', 'schedulesDutyTeacher']);

        $schedules = $duty->schedulesDutyTeacher
            ->filter(fn($s) => $s->start_at <= $end_at && $s->end_at >= $start_at);

        $allDates = [];
        foreach ($schedules as $schedule) {
            $dates = is_array($schedule->dates) ? $schedule->dates : json_decode($schedule->dates, true);

            foreach ($dates as $dateStr => $value) {
                if ($dateStr >= $start_at && $dateStr <= $end_at) {
                    $allDates[$dateStr] = $value;
                }
            }
        }

        foreach (CarbonPeriod::create($start_at, $end_at) as $date) {
            $dateStr = $date->format('Y-m-d');
            if (!isset($allDates[$dateStr])) {
                $allDates[$dateStr] = [null, null, []];
            }
        }

        // Urutkan tanggal ascending
        ksort($allDates);

        return view('hrms::service.schedules_teacher.manage.collective.show', compact(
            'duty', 'start_at', 'end_at', 'allDates'
        ));
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $employee = $request->user()->employee;

        if ($this->storeEmployeeSchedule($request->transformed()->toArray(), $employee)) {
            return redirect()->back()->with('success', 'Jadwal piket berhasil diterapkan!');
        }
        return redirect()->fail();
    }


    public function update(Employee $duty, Request $request)
    {
        $request->validate([
            'date'  => 'required|date',
            'shift' => 'required|integer',
            'room'  => 'required|integer',
        ]);

        $date = Carbon::parse($request->date);
        
        $startOfMonth = $date->copy()->startOfMonth()->toDateString();
        $endOfMonth   = $date->copy()->endOfMonth()->toDateString();

        $schedule = $duty->schedulesDutyTeacher()
            ->whereDate('start_at', '<=', $endOfMonth)
            ->whereDate('end_at', '>=', $startOfMonth)
            ->first();

        if (!$schedule) {
            $dates = [];

            foreach (CarbonPeriod::create($startOfMonth, $endOfMonth) as $day) {
                $dates[$day->format('Y-m-d')] = [null, null, []];
            }

            $schedule = $duty->schedulesDutyTeacher()->create([
                'start_at' => $startOfMonth,
                'end_at' => $endOfMonth,
                'dates' => $dates,
                'workdays_count' => 0,
            ]);
        } else {
            $dates = is_array($schedule->dates) ? $schedule->dates : json_decode($schedule->dates, true);

            foreach (CarbonPeriod::create($schedule->start_at, $schedule->end_at) as $day) {
                $dateStr = $day->format('Y-m-d');
                if (!isset($dates[$dateStr])) {
                    $dates[$dateStr] = [null, null, []];
                }
            }
        }

        $targetDate = $date->format('Y-m-d');

        if (!isset($dates[$targetDate])) {
            $dates[$targetDate] = [
                '08:00',       
                '16:00',       
                [$request->shift => (int) $request->room]  
            ];
        } else {
            $dates[$targetDate][2][$request->shift] = (int) $request->room;

            if (empty($dates[$targetDate][0])) {
                $dates[$targetDate][0] = '08:00';
            }
            if (empty($dates[$targetDate][1])) {
                $dates[$targetDate][1] = '16:00';
            }
        }


        $workCount = 0;
        foreach ($dates as $day => $values) {
            if (!empty($values[0]) && !empty($values[1])) {
                $workCount++;
            }
        }
        $schedule->dates = $dates;
        $schedule->workdays_count = $workCount;
        $schedule->save();

        return back()->with('success', 'Data jadwal berhasil diperbarui.');
    }


    public function destroyOneSch(Employee $duty, Request $request){
        $date = $request->input('date');
        $shift = $request->input('shift');

        $dated = Carbon::parse($date);
        $startOfMonth = $dated->copy()->startOfMonth()->toDateString();
        $endOfMonth   = $dated->copy()->endOfMonth()->toDateString();

        $schedules = $duty->schedulesDutyTeacher()
            ->whereDate('start_at', '<=', $endOfMonth)
            ->whereDate('end_at', '>=', $startOfMonth)
            ->get();

        if ($schedules->isEmpty()) {
            return back()->with('error', 'Jadwal tidak ditemukan untuk periode ini.');
        }

        $targetSchedule = null;
        foreach ($schedules as $schedule) {
            $dates = is_array($schedule->dates) ? $schedule->dates : json_decode($schedule->dates, true);
            if (isset($dates[$date])) {
                $targetSchedule = $schedule;
                break;
            }
        }

        if (!$targetSchedule) {
            return back()->with('error', 'Jadwal pada tanggal tersebut tidak ditemukan.');
        }

        $dates = is_array($targetSchedule->dates) ? $targetSchedule->dates : json_decode($targetSchedule->dates, true);

        if (isset($dates[$date][2][$shift])) {
            unset($dates[$date][2][$shift]);

            if (empty($dates[$date][2])) {
                $dates[$date][0] = null;
                $dates[$date][1] = null;
            }

            $workCount = 0;
            foreach ($dates as $day => $values) {
                if (!empty($values[0]) && !empty($values[1])) {
                    $workCount++;
                }
            }

            $targetSchedule->dates = $dates;
            $targetSchedule->workdays_count = $workCount;
            $targetSchedule->save();
        }

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }




    
    public function destroySch(Request $request)
    {
        $emp_id_arr = $request->input('emp_id_arr');
        $start_at   = Carbon::parse($request->start_at); 
        $end_at     = Carbon::parse($request->end_at);   
        $dayz       = $request->day; 

        $employeeIds = [];

        if (is_array($emp_id_arr) && count($emp_id_arr) > 0) {
            $employeeIds = $emp_id_arr;
        } elseif ($request->has('emp_id')) {
            $employeeIds = [$request->input('emp_id')];
        }

        foreach ($employeeIds as $emp_id) {
            $monthPeriod = CarbonPeriod::create(
                $start_at->copy()->startOfMonth(),
                '1 month',
                $end_at->copy()->startOfMonth()
            );

            foreach ($monthPeriod as $month) {
                $monthStart = $month->copy()->startOfMonth()->format('Y-m-d');
                $monthEnd   = $month->copy()->endOfMonth()->format('Y-m-d');

                $duty = EmployeeTeacherDuty::where('empl_id', $emp_id)
                    ->where('start_at', $monthStart)
                    ->where('end_at', $monthEnd)
                    ->first();

                if (!$duty) {
                    continue;
                }

                $dates = $duty->dates;

                foreach ($dates as $dateKey => $val) {
                    $date = Carbon::parse($dateKey);

                    if (
                        $date->betweenIncluded($start_at, $end_at)
                        && $date->format('l') === $dayz
                    ) {
                        $dates[$dateKey] = [];
                    }
                }

                $duty->dates = $dates;
                $duty->save();
            }
        }


        return back()->with('success', 'Jadwal berhasil dikosongkan untuk periode yang dipilih.');
    }


    public function destroy(Employee $duty, Request $request)
    {
        $start = Carbon::parse($request->input('start_at'))->startOfDay();
        $end   = Carbon::parse($request->input('end_at'))->endOfDay();

        if ($start->gt($end)) {
            [$start, $end] = [$end, $start];
        }

        $schedules = $duty->schedulesDutyTeacher()
            ->whereDate('start_at', '<=', $end)
            ->whereDate('end_at', '>=', $start)
            ->get();

        DB::transaction(function () use ($schedules, $start, $end) {
            foreach ($schedules as $schedule) {
                $sStart = Carbon::parse($schedule->start_at)->startOfDay();
                $sEnd   = Carbon::parse($schedule->end_at)->endOfDay();

                $rangeStart = $start->greaterThan($sStart) ? $start : $sStart;
                $rangeEnd   = $end->lessThan($sEnd) ? $end : $sEnd;

                $dates = is_array($schedule->dates) ? $schedule->dates : json_decode($schedule->dates, true);

                foreach ($dates as $dateKey => $vals) {
                    $d = Carbon::parse($dateKey);
                    if ($d->gte($rangeStart) && $d->lte($rangeEnd)) {
                        $dates[$dateKey] = [null, null, []];
                    }
                }

                $workCount = 0;
                foreach ($dates as $v) {
                    if (!empty($v[0]) && !empty($v[1])) {
                        $workCount++;
                    }
                }

                $schedule->update([
                    'dates' => $dates,
                    'workdays_count' => $workCount,
                ]);
            }
        });

        return back()->with('success', 'Jadwal pada rentang tersebut berhasil dihapus.');
    }


}
