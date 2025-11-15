<?php

namespace Modules\HRMS\Http\Controllers\Service\Attendance;

use Illuminate\Http\Request;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\Core\Models\CompanyMoment;
use Modules\HRMS\Enums\WorkShiftEnum;
use Modules\HRMS\Enums\TeacherShiftEnum;
use Modules\Academic\Models\AcademicSubject;
use Modules\HRMS\Models\EmployeeContract;
use Modules\HRMS\Models\EmployeeSchedule;
use Modules\HRMS\Repositories\EmployeeScheduleRepository;
use Modules\HRMS\Http\Requests\Service\Attendance\Collective\StoreRequest;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use App\Models\References\GradeLevel;

class CollectiveController extends Controller
{
    use EmployeeScheduleRepository;

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', EmployeeSchedule::class);

        //$month = $request->old('month', $request->get('month', date('Y-m')));
        $start_at = $request->get('start_at', now()->startOfWeek());
        $end_at = $request->get('end_at', now()->endOfWeek());

        $workshifts = TeacherShiftEnum::cases();

        $dates = [];

        for ($date = $start_at->copy(); $date->lte($end_at); $date->addDay()) {
            if ($date->dayOfWeek !== 0) {
                $dates[] = $date->toDateString();
            }
        }

        $moments = CompanyMoment::holiday()
            ->whereBetween('date', [$start_at->toDateString(), $end_at->toDateString()])
            ->get();

        $contracts = EmployeeContract::with(['employee.user', 'position.position' => fn($w) => $w->with('department')])
            ->whereHas('position.position', fn($p) => $p->where('type', PositionTypeEnum::GURU->value))
             ->whereHas('employee', function ($q) {
                $q->where('grade_id', userGrades());
            })
            ->active()
            ->get();

        $employees = Employee::where('grade_id', userGrades())->with('user')->whereNull('deleted_at')->get();

        $worktime_default = setting('cmp_empl_default_worktimes');
        $grades = GradeLevel::where('grade_id', userGrades())->pluck('id');
        $academicSubject = AcademicSubject::whereIn('level_id', $grades)->get();

        return view('hrms::service.attendance.collective.create', compact('dates', 'workshifts', 'contracts', 'moments', 'worktime_default', 'employees', 'academicSubject'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $dates = [];

        $dates = $request->input('dates');
        $filteredDates = [];

        foreach ($dates as $date => $shifts) {
            $validShifts = [];

            foreach ($shifts as $shift) {
                if (!empty($shift[0]) && !empty($shift[1])) {
                    $validShifts[] = $shift;
                }
            }

            if (count($validShifts) > 0) {
                $filteredDates[$date] = $validShifts;
            }
        }

        if (count($filteredDates) == 0) {
            return redirect()->back()->with('danger', 'Shift tidak boleh kosong pada alokasi jadwal');
        } else if (empty($request->input('empl_ids')) || empty($request->input('dates'))) {
            return redirect()->back()->with('danger', 'Belum ada karyawan yang dipilih untuk alokasi jadwal');
        }

        if (EmployeeSchedule::upsert($request->transformed()->toArray(), ['empl_id', 'start_at', 'end_at'], ['dates', 'workdays_count'])) {
            $request->user()->log('membuat ' . ($count = count($request->transformed()->toArray())) . ' jadwal kerja karyawan untuk periode ' . ($request->input('month')) . ' secara kolektif');

            return redirect()->next()->with('success', 'Berhasil meregistrasikan ' . $count . ' jadwal kerja karyawan untuk periode ' . ($request->input('month')) . ' secara kolektif.');
        }
     return redirect()->fail();
    }
}
