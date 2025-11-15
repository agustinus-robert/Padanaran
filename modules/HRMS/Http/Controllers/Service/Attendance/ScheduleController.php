<?php

namespace Modules\HRMS\Http\Controllers\Service\Attendance;

use Illuminate\Http\Request;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\Core\Models\CompanyMoment;
use Modules\HRMS\Enums\ObShiftEnum;
use Modules\HRMS\Enums\TeacherShiftEnum;
use Modules\HRMS\Enums\StudentShiftEnum;
use Modules\Academic\Models\AcademicSubject;
use Modules\HRMS\Models\EmployeeScheduleCategory;
use Modules\HRMS\Models\EmployeeScheduleLesson;
use App\Models\References\GradeLevel;
use Modules\Academic\Models\AcademicSubjectCategory;
use Modules\HRMS\Enums\WorkShiftEnum;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeSchedule;
use Modules\HRMS\Repositories\EmployeeScheduleRepository;
use Modules\HRMS\Http\Requests\Service\Attendance\Schedule\StoreRequest;
use Modules\HRMS\Http\Requests\Service\Attendance\Schedule\UpdateRequest;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\EmployeeScanLog;

class ScheduleController extends Controller
{
    use EmployeeScheduleRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeSchedule::class);

        $start_at = $request->get('start_at', now()->startOfWeek()->toDateString());
        $end_at = $request->get('end_at', now()->endOfWeek()->toDateString());

        $employees = Employee::with([
            'user.meta',
            'contract.position.position',
            'schedules' => fn($schedule) => $schedule->whenMonth($request->get('month', date('Y-m'))),
          //  'contractWithin7Days.schedules' => fn($schedule) => $schedule->whenMonth($request->get('month', date('Y-m'))),
        ])
            ->search($request->get('search'))
            ->where('grade_id', userGrades())
            ->whenTrashed($request->get('trash'))
            ->whereDoesntHave('contract.position', function ($position) {
                $position->whereHas('position', function ($type) {
                    $type->where('type', PositionTypeEnum::GURU);
                });
            })
            // ->whereDoesntHave('contract.position', function ($position) {
            //     $position->whereHas('position', function ($type) {
            //         $type->where('type', PositionTypeEnum::GURUBK);
            //     });
            // })
            ->paginate($request->get('limit', 10));

        return view('hrms::service.attendance.schedules.index', compact('employees','start_at','end_at'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', EmployeeSchedule::class);

        $employee = Employee::where('grade_id', userGrades())->findOrFail($request->get('employee'));

        $month = $request->old('month', $request->get('month', date('Y-m')));

        $start_at = $request->get('start_at', now()->startOfWeek());
        $end_at = $request->get('end_at', now()->endOfWeek());


        $defaultCategoryAcademic = AcademicSubjectCategory::where('grade_id', userGrades())->get();
        $gradeLevel = GradeLevel::where('grade_id', userGrades())->get();
        //set pertamat kali di pengajaran umum
        $defaultLessons = EmployeeScheduleLesson::where(['category_schedule_id' => 1])->get();
        $defaultCategoryLessons = EmployeeScheduleCategory::where('grade_id', userGrades())->get();

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

      //  $workshifts = TeacherShiftEnum::cases();

        $dates = [];

        for ($date = $start_at->copy(); $date->lte($end_at); $date->addDay()) {
            if ($date->dayOfWeek !== 0) {
                $dates[] = $date->toDateString();
            }
        }

        $moments = CompanyMoment::holiday()->whenMonthOfYear($month)->get();
        $academicSubject = AcademicSubject::whereIn('level_id', $gradeLevel->pluck('id'))->get();

        return view('hrms::service.attendance.schedules.create', compact('employee', 'dates', 'workshifts', 'moments', 'academicSubject', 'defaultLessons', 'defaultCategoryLessons', 'defaultCategoryAcademic', 'gradeLevel', 'start_at', 'end_at'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($schedule = $this->storeEmployeeSchedule($request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Jadwal kerja karyawan baru atas nama <strong>' . $schedule->employee->user->name . '</strong> berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeSchedule $schedule, Request $request)
    {
        $this->authorize('update', $schedule);

        $gradeLevel = GradeLevel::where('grade_id', userGrades())->get()->pluck('id');
        $workshifts = TeacherShiftEnum::cases();
        $dates = [];

        $academicSubject = AcademicSubject::whereIn('level_id', $gradeLevel)->get();

        $defaultCategoryAcademic = AcademicSubjectCategory::get();
        //set pertamat kali di pengajaran umum
        $defaultLessons = EmployeeScheduleLesson::where(['category_schedule_id' => 1])->get();
        $defaultCategoryLessons = EmployeeScheduleCategory::where('grade_id', userGrades())->get();

        $start_at = $request->get('start_at', now()->startOfWeek());
        $end_at = $request->get('end_at', now()->endOfWeek());

        for ($date = $start_at->copy(); $date->lte($end_at); $date->addDay()) {
            if ($date->dayOfWeek !== 0) {
                $dates[] = $date->toDateString();
            }
        }

        $moments = CompanyMoment::holiday()->whenMonthOfYear($schedule->period)->get();

        return view('hrms::service.attendance.schedules.show', compact('schedule', 'workshifts', 'dates', 'moments', 'academicSubject', 'defaultCategoryAcademic', 'gradeLevel', 'defaultLessons', 'defaultCategoryLessons', 'start_at', 'end_at'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeSchedule $schedule, UpdateRequest $request)
    {
        if ($schedule = $this->updateEmployeeSchedule($schedule, $request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Jadwal kerja karyawan baru atas nama <strong>' . $schedule->employee->user->name . '</strong> berhasil diperbarui.');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeSchedule $schedule)
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
