<?php

namespace Modules\Portal\Http\Controllers\ScheduleTeacher;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Models\CompanyMoment;
use Modules\HRMS\Enums\WorkShiftEnum;
use Modules\HRMS\Repositories\EmployeeScheduleRepository;
use Modules\HRMS\Repositories\EmployeeRepository;
use Modules\Portal\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    use EmployeeRepository, EmployeeScheduleRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $employee = $request->user()->employee;

        $month      = Carbon::parse($request->get('month', now()));
        $start_at   = $month->copy()->startOfMonth()->format("Y-m-d");
        $end_at     = $month->copy()->endOfMonth()->format("Y-m-d");
        $workshifts = WorkShiftEnum::cases();
        $moments    = CompanyMoment::holiday()->whenMonthOfYear($month)->get()->groupBy('date');

        $refSchedules  = $employee->schedules()->whenMonth($month->format('Y-m'))->first();
        if (empty($refSchedules)) {
            return redirect()->back()->with('danger', 'Mohon maaf, jadwal kamu belum ditetapkan di sistem, harap hubungi Admin. Terima kasih.');
        }
        $schedules     = $this->iterateSchedule($refSchedules->dates);
        $scheduleCount = $this->countSchedule($refSchedules->dates);

        $vacations = $employee->vacations()
            ->with('quota.category')
            ->whereExtractedDatesBetween($month->startOfMonth()->format("Y-m-d"), $month->endOfMonth()->format("Y-m-d"))
            ->get()
            ->filter(fn($vacation) => $vacation->hasAllApprovableResultIn('APPROVE'))
            ->pluck('dates')
            ->filter(fn($date) => empty($date->first()['cashable']))->flatten(1)->groupBy('d')->flatten(1)->unique();

        return view('portal::schedules_teacher.index', compact('month', 'workshifts', 'employee', 'moments', 'schedules', 'vacations', 'scheduleCount'));
    }
}
