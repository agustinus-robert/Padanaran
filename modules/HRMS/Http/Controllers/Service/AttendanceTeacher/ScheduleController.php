<?php

namespace Modules\HRMS\Http\Controllers\Service\AttendanceTeacher;

use Illuminate\Http\Request;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\Core\Models\CompanyMoment;
use Modules\HRMS\Enums\ObShiftEnum;
use Modules\HRMS\Enums\TeacherShiftEnum;
use Modules\HRMS\Enums\WorkShiftEnum;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeScheduleTeacher;
use Modules\HRMS\Repositories\EmployeeScheduleTeacherRepository;
use Modules\HRMS\Http\Requests\Service\Attendance\Schedule\StoreRequest;
use Modules\HRMS\Http\Requests\Service\Attendance\Schedule\UpdateRequest;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\EmployeeTeacherScanLog;

class ScheduleController extends Controller
{
    use EmployeeScheduleTeacherRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeScheduleTeacher::class);

        $employees = Employee::with([
            'user.meta',
            'contract.position.position',
            'schedulesTeachers' => fn($schedule) => $schedule->whenMonth($request->get('month', date('Y-m'))),
            'contractWithin7Days.schedules' => fn($schedule) => $schedule->whenMonth($request->get('month', date('Y-m'))),
        ])
            ->isTeacher()
            ->search($request->get('search'))
            ->whenTrashed($request->get('trash'))
            ->paginate($request->get('limit', 10));


        return view('hrms::service.attendance-teacher.schedules.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', EmployeeScheduleTeacher::class);

        $employee = Employee::findOrFail($request->get('employee'));

        $month = $request->old('month', $request->get('month', date('Y-m')));

        switch ($employee->position->position->type) {
            case PositionTypeEnum::BACKOFFICE:
                $workshifts = WorkShiftEnum::cases();
                break;

            case PositionTypeEnum::TEACHER:
                $workshifts = TeacherShiftEnum::cases();
                break;

            case PositionTypeEnum::SECURITY:
            case PositionTypeEnum::NONSTAF:
                $workshifts = ObShiftEnum::cases();
                break;

            default:
                $workshifts = [];
                break;
        }

        $dates = [];
        for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($month)), date('Y', strtotime($month))); $i++) {
            $dates[] = $month . '-' . str_pad($i, 2, 0, STR_PAD_LEFT);
        }

        $moments = CompanyMoment::holiday()->whenMonthOfYear($month)->get();

        return view('hrms::service.attendance-teacher.schedules.create', compact('employee', 'dates', 'workshifts', 'moments'));
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
    public function show(EmployeeScheduleTeacher $schedule, Request $request)
    {
        $this->authorize('update', $schedule);

        $workshifts = WorkShiftEnum::cases();

        $dates = [];
        for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($schedule->period)), date('Y', strtotime($schedule->period))); $i++) {
            $dates[] = $schedule->period . '-' . str_pad($i, 2, 0, STR_PAD_LEFT);
        }

        $moments = CompanyMoment::holiday()->whenMonthOfYear($schedule->period)->get();

        return view('hrms::service.attendance-teacher.schedules.show', compact('schedule', 'workshifts', 'dates', 'moments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeScheduleTeacher $schedule, UpdateRequest $request)
    {
        if ($schedule = $this->updateEmployeeSchedule($schedule, $request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Jadwal kerja karyawan baru atas nama <strong>' . $schedule->contract->employee->user->name . '</strong> berhasil diperbarui.');
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
            return redirect()->next()->with('success', 'Jadwal kerja karyawan baru atas nama <strong>' . $schedule->contract->employee->user->name . '</strong> berhasil dihapus.');
        }
        return redirect()->fail();
    }

    /**
     * batch create employee schedule for one year.
     */
    public function generate(Request $request)
    {
        $this->authorize('store', EmployeeScheduleTeacher::class);
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
        $this->authorize('store', EmployeeTeacherScanLog::class);

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
        $this->authorize('store', EmployeeTeacherScanLog::class);

        if ($this->generateTeacherPresences()) {
            return redirect()->next()->with('success', 'Presensi guru berhasil dibuat.');
        }
        return redirect()->fail();
    }
}
