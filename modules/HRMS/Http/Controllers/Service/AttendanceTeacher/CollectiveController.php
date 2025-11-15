<?php

namespace Modules\HRMS\Http\Controllers\Service\AttendanceTeacher;

use Illuminate\Http\Request;
use Modules\Core\Enums\PositionTypeEnum;
use Modules\Core\Models\CompanyMoment;
use Modules\HRMS\Enums\WorkShiftEnum;
use Modules\HRMS\Models\EmployeeContract;
use Modules\HRMS\Models\EmployeeScheduleTeacher;
use Modules\HRMS\Repositories\EmployeeScheduleRepository;
use Modules\HRMS\Http\Requests\Service\Attendance\Collective\StoreRequest;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;

class CollectiveController extends Controller
{
    use EmployeeScheduleRepository;

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', EmployeeScheduleTeacher::class);

        $month = $request->old('month', $request->get('month', date('Y-m')));
        $workshifts = WorkShiftEnum::cases();

        $dates = [];
        for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($month)), date('Y', strtotime($month))); $i++) {
            $dates[] = $month . '-' . str_pad($i, 2, 0, STR_PAD_LEFT);
        }

        $moments = CompanyMoment::holiday()->whenMonthOfYear($month)->get();

        $contracts = EmployeeContract::with(['employee.user', 'position.position' => fn($w) => $w->with('department')])
            ->whereHas('position.position', fn($p) => $p->where('type', PositionTypeEnum::TEACHER->value))
            ->active()
            ->get();

        $worktime_default = setting('cmp_empl_default_worktimes');

        return view('hrms::service.attendance-teacher.collective.create', compact('dates', 'workshifts', 'contracts', 'moments', 'worktime_default'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if (EmployeeScheduleTeacher::upsert($request->transformed()->toArray(), ['empl_id', 'start_at', 'end_at'], ['dates', 'workdays_count'])) {
            $request->user()->log('membuat ' . ($count = count($request->transformed()->toArray())) . ' jadwal kerja guru untuk periode ' . ($request->input('month')) . ' secara kolektif');

            return redirect()->next()->with('success', 'Berhasil meregistrasikan ' . $count . ' jadwal kerja guru untuk periode ' . ($request->input('month')) . ' secara kolektif.');
        }
        return redirect()->fail();
    }
}
