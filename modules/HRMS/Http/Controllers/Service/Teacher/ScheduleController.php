<?php

namespace Modules\HRMS\Http\Controllers\Service\Teacher;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\Core\Models\CompanyMoment;
use Modules\HRMS\Enums\WorkShiftEnum;
use Modules\HRMS\Repositories\EmployeeScheduleTeacherRepository;
use Modules\HRMS\Repositories\EmployeeRepository;
use Modules\Portal\Http\Controllers\Controller;
use Modules\HRMS\Models\EmployeeScheduleTeacher;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Http\Requests\Service\Attendance\Schedule\StoreRequest;
use Modules\HRMS\Http\Requests\Service\Attendance\Schedule\UpdateRequest;
use Modules\HRMS\Enums\ObShiftEnum;
use Modules\HRMS\Enums\TeacherShiftEnum;
use Modules\HRMS\Enums\StudentShiftEnum;
use Modules\Academic\Models\AcademicSubject;
use Modules\HRMS\Models\EmployeeScheduleCategory;
use Modules\HRMS\Models\EmployeeScheduleLesson;
use App\Models\References\GradeLevel;
use Modules\Academic\Models\AcademicSubjectCategory;
use Modules\HRMS\Models\EmployeeScanLog;
use Modules\Administration\Excel\Exports\ExportScheduleTeacher;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Modules\Academic\Models\AcademicClassroom;
use App\Events\TeacherMeetUpsert;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;


class ScheduleController extends Controller
{
    use EmployeeRepository, EmployeeScheduleTeacherRepository;

    /**
     * Display a listing of the resource.
     */
     public function index(Request $request)
    {
        $this->authorize('access', EmployeeScheduleTeacher::class);

        $start_at = $request->get('start_at', now()->startOfMonth()->toDateString());
        $end_at   = $request->get('end_at', now()->endOfMonth()->toDateString());


        $employees = Employee::with([
            'user.meta',
            'contract.position.position',
            'schedulesTeachers' => function ($query) use ($start_at, $end_at) {
                $query->where(function ($q) use ($start_at, $end_at) {
                    $q->where('start_at', '<=', $end_at)
                    ->where('end_at', '>=', $start_at); // overlap check
                });
            },
        ])
        ->search($request->get('search'))
        ->where('grade_id', userGrades())
        ->whenTrashed($request->get('trash'))
        ->whereHas('contract.position', function ($position) {
            $position->whereHas('position', function ($type) {
                $type->where('type', PositionTypeEnum::GURU);
            });
        })
        ->paginate($request->get('limit', 10));

        return view('hrms::service.teachers.index', compact('employees','start_at','end_at'));
    }

    public function export()
    {
        return Excel::download(new ExportScheduleTeacher(), 'jadwal_pengajar.xlsx');
    }

    public function importExcel(Request $request)
    {

        $file = $request->file('scheduleFile');
        
        if (!$request->hasFile('scheduleFile') || !$request->file('scheduleFile')->isValid()) {
            return redirect()->back()->with('danger', 'Silakan unggah file Excel terlebih dahulu.');
        }

        $type = $request->input('empl_category_id');

        $spreadsheet = IOFactory::load($file->getRealPath());
        $worksheet = $spreadsheet->getActiveSheet();

        //2025-07-01-2025-09-01
        $start_at = Carbon::parse($request->start_at);
        $end_at   = Carbon::parse($request->end_at);

        $lastRow = $worksheet->getHighestRow();
        $lastColumn = $worksheet->getHighestColumn();
        $lastColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($lastColumn);

        $validDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $excelDayShifts = []; // ['Senin' => ['Shift 1' => ['EMP01' => 'MAPEL', ...], ...]
        $employeeIds = [];

        // Ambil employee ID dari kolom B (kolom 2)
        for ($row = 5; $row <= $lastRow; $row++) {
            $emplId = $worksheet->getCell("B{$row}")->getValue();
            if (!empty($emplId)) {
                $employeeIds[$row] = $emplId;
            }
        }

        // Ambil data shift berdasarkan hari (baris ke-2: nama hari, baris ke-4: nama shift)
        for ($col = 3; $col <= $lastColumnIndex; $col += 9) {
            $dayCell = $worksheet->getCellByColumnAndRow($col, 2);
            $dayValue = trim($dayCell->getValue());

            if ($dayValue && in_array($dayValue, $validDays)) {
                $shifts = [];

                for ($shiftCol = $col; $shiftCol < $col + 9; $shiftCol++) {
                    $shiftCell = $worksheet->getCellByColumnAndRow($shiftCol, 4);
                    $shiftName = $shiftCell->getValue();

                    $shiftData = [];

                    foreach ($employeeIds as $row => $emplId) {
                        $dataValue = $worksheet->getCellByColumnAndRow($shiftCol, $row)->getValue();
                        
                        if (!empty($dataValue)) {
                            $shiftData[$emplId] = optional(AcademicSubject::where('kd', $dataValue)->first())->id; // bisa mapel atau tanda X
                        }
                    }

                    if ($shiftName) {
                        $shifts[$shiftName] = $shiftData;
                    }
                }

                $excelDayShifts[$dayValue] = $shifts;
            }
        }

        // Mapping nama hari Inggris ke Bahasa Indonesia
        $dayTranslations = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];

        $dateShiftsUser = [];
        $period = CarbonPeriod::create($start_at, $end_at);

        $allEmployeeIds = array_unique($employeeIds);

        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $englishDay = $date->format('l');
            $dayIndo = $dayTranslations[$englishDay] ?? null;

            $hasShift = $dayIndo && isset($excelDayShifts[$dayIndo]);

            foreach ($allEmployeeIds as $emplId) {
                $dateShiftsUser[$emplId][$dateStr] = [];

                if ($hasShift) {
                    $shifts = $excelDayShifts[$dayIndo];

                    foreach ($shifts as $shiftName => $employeeMap) {
                        if (isset($employeeMap[$emplId])) {
                            $subjectName = $employeeMap[$emplId];

                            $shiftNumber = (int) filter_var($shiftName, FILTER_SANITIZE_NUMBER_INT);
                            $enum = \Modules\HRMS\Enums\TeacherShiftEnum::tryFrom($shiftNumber);
                            $inOut = $enum ? [$enum->defaultTime()['in'][0], $enum->defaultTime()['out'][0]] : [null, null];

                            $dateShiftsUser[$emplId][$dateStr][$shiftName] = [
                                0 => $inOut[0],
                                1 => $inOut[1],
                                'lesson' => [$subjectName],
                            ];
                        }
                    }
                } else {
                    foreach (range(1, 9) as $i) {
                        $enum = \Modules\HRMS\Enums\TeacherShiftEnum::tryFrom($i);
                        $inOut = $enum ? [$enum->defaultTime()['in'][0], $enum->defaultTime()['out'][0]] : [null, null];

                        $dateShiftsUser[$emplId][$dateStr] = [];
                    }
                }
            }
        }

        $userShift = [];
        $totalMonthly = [];
        foreach ($dateShiftsUser as $user => $monthLy) {
            $userShift[$user] = [];
            $totalMonthly[$user] = [];
            foreach ($monthLy as $dateKey => $dateValue) {
                $userShift[$user][$dateKey] = [];
                $totalMonthly[$user][$dateKey] = 0;
                foreach ($dateValue as $shiftKey => $shiftValue) {
                    $userShift[$user][$dateKey][$shiftKey] = 0;
                    $validShifts = 0;
                    foreach ($shiftValue as $shift) {
                        if ($shift !== [null, null]) {
                            $validShifts++;
                            $totalMonthly[$user][$dateKey]++;
                        }
                    }

                    if ($userShift[$user][$dateKey][$shiftKey]) {
                        $userShift[$user][$dateKey][$shiftKey] = 0;
                    }


                    $userShift[$user][$dateKey][$shiftKey] += $validShifts;
                }
            }
        }


        $arrData = [
            'data' => $dateShiftsUser,
            'countShift' => $userShift,
            'totalUserShiftMonthly' => $totalMonthly,
            'updateShifts' => $excelDayShifts,
            'empl_category_id' => $request->input('empl_category_id'),
            'smt_id' => $request->input('smt_id')
        ];


        $employee = $request->user()->employee;
        if ($this->storeEmployeeSchedule2($arrData, $employee)) {
            return redirect()->back()->with('success', 'Data berhasil diproses!');
        }
        return redirect()->fail();
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', EmployeeScheduleTeacher::class);

        $employee = Employee::findOrFail($request->get('employee'));

        $month = $request->old('month', $request->get('month', date('Y-m')));

        $start_at = $request->get('start_at', now()->startOfWeek());
        $end_at = $request->get('end_at', now()->endOfWeek());


        $defaultCategoryAcademic = AcademicSubjectCategory::where('grade_id', userGrades())->get();
        $gradeLevel = GradeLevel::where('grade_id', userGrades())->get();
        //set pertamat kali di pengajaran umum
        $defaultLessons = EmployeeScheduleLesson::where('category_schedule_id', 1)->get();
        $defaultCategoryLessons = EmployeeScheduleCategory::get();
        $defaultClassroom = AcademicClassroom::whereNull('deleted_at')->get();
        
        switch ($employee->position->position->type) {
            case PositionTypeEnum::GURU:
                $workshifts = WorkShiftEnum::cases();
                break;

            case PositionTypeEnum::MURID:
                $workshifts = StudentShiftEnum::cases();
                break;

            default:
                $workshifts = WorkShiftEnum::cases();
                break;
        }

        $workshifts = TeacherShiftEnum::cases();

        $dates = [];

        for ($date = $start_at->copy(); $date->lte($end_at); $date->addDay()) {
            if ($date->dayOfWeek !== 0) {
                $dates[] = $date->toDateString();
            }
        }

        $moments = CompanyMoment::holiday()->whenMonthOfYear($month)->get();
        $academicSubject = AcademicSubject::whereIn('level_id', $gradeLevel->pluck('id'))->get();

        return view('hrms::service.teachers.create', compact('employee', 'dates', 'workshifts', 'moments', 'academicSubject', 'defaultLessons', 'defaultCategoryLessons', 'defaultCategoryAcademic', 'gradeLevel', 'start_at', 'end_at', 'defaultClassroom'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->transformed()->toArray();
        $key = 'schedule_' . time();
        cache()->put("progress_$key", 0);

        DB::transaction(function () use ($data, $key, &$schedule) {

            $schedule = $this->storeEmployeeSchedule($data);

            if (!$schedule) {
                throw new \Exception("Gagal membuat schedule");
            }

            event(new TeacherMeetUpsert($data, $key));
        });

        return redirect()
            ->next()
            ->with('progress_key', $key)
            ->with('success', 'Jadwal kerja karyawan baru atas nama <strong>' .
                $schedule->employee->user->name .
                '</strong> berhasil dibuat.');
    }



  public function updateCategory(Request $request, $date, $employeeId)
{
    $schedule = EmployeeScheduleTeacher::where('empl_id', $employeeId)
        ->where('start_at', '<=', $date)
        ->where('end_at', '>=', $date)
        ->firstOrFail();

    $dates = json_decode($schedule->dates, true);


    if (!isset($dates[$date])) {
        return redirect()->back()->withErrors('Tanggal tidak ditemukan dalam jadwal');
    }

    // Ambil category_id dari input form, default 1 kalau tidak ada
    $newCategory = $request->input('category_id', 1);

    // Update category_id untuk tanggal tersebut
    $dates[$date]['category_id'] = $newCategory;

    $schedule->dates = $dates;
    $schedule->save();

    return redirect()->back()->with('success', 'Kategori jadwal berhasil diubah.');
}

    /**
     * Display the specified resource.
     */
    public function show(Employee $schedule, Request $request)
    {
        $this->authorize('update', $schedule);

        $gradeLevel = GradeLevel::where('grade_id', userGrades())->get();
        $workshifts = TeacherShiftEnum::cases();
        $dates = [];

        ///whereIn('grade_id', $gradeLevel->pluck('id'))
        $academicSubject = AcademicSubject::get();

        $defaultCategoryAcademic = AcademicSubjectCategory::where('grade_id', userGrades())->get();
        //set pertamat kali di pengajaran umum
        $defaultLessons = EmployeeScheduleLesson::where('category_schedule_id', 1)->get();
        $defaultCategoryLessons = EmployeeScheduleCategory::where('grade_id', userGrades())->get();

        $start_at = $request->start_at
            ? Carbon::parse($request->start_at)->format('Y-m-d')
            : now()->format('Y-m-d');

        $end_at = $request->end_at
            ? Carbon::parse($request->end_at)->format('Y-m-d')
            : now()->format('Y-m-d');

        $schedules = $schedule->schedulesTeachers
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

        ksort($allDates);

        $moments = CompanyMoment::holiday()->whenMonthOfYear($schedule->period)->get();
        return view('hrms::service.teachers.show', compact('allDates', 'schedule', 'workshifts', 'dates', 'moments', 'academicSubject', 'defaultCategoryAcademic', 'gradeLevel', 'defaultLessons', 'defaultCategoryLessons', 'start_at', 'end_at'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeScheduleTeacher $schedule, UpdateRequest $request)
    {
        if ($schedule = $this->updateEmployeeSchedule($schedule, $request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Jadwal kerja karyawan baru atas nama <strong>' . $schedule->employee->user->name . '</strong> berhasil diperbarui.');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeScheduleTeacher $schedule)
    {
        $this->authorize('destroy', $schedule);

        if ($schedule = $this->destroyEmployeeSchedule($schedule)) {
            return redirect()->next()->with('success', 'Jadwal kerja karyawan baru atas nama <strong>' . $schedule->employee->user->name . '</strong> berhasil dihapus.');
        }
        return redirect()->fail();
    }

    /**
     * batch create employee schedule for one year.
     */
    public function generate(Request $request)
    {
        $this->authorize('store', EmployeeSchedule::class);
        $month = $request->input('target_month');
        if ($this->generateMonthlySchedules($month)) {
            return redirect()->next()->with('success', 'Jadwal kerja karyawan baru berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * batch create employee schedule for one year.
     */
    public function presence()
    {
        $this->authorize('store', EmployeeScanLog::class);

        if ($this->generatePresences()) {
            return redirect()->next()->with('success', 'Presensi karyawan berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * batch create teacher presence for one year.
     */
    public function teacher_presence()
    {
        $this->authorize('store', EmployeeScanLog::class);

        if ($this->generateTeacherPresences()) {
            return redirect()->next()->with('success', 'Presensi guru berhasil dibuat.');
        }
        return redirect()->fail();
    }
}
