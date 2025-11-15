<?php

namespace Modules\Finance\Http\Controllers\Report;

use Carbon\Carbon;
use Modules\Core\Models\CompanyDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeDataRecapitulation;
use Modules\HRMS\Models\EmployeeOvertime;
use Modules\HRMS\Models\EmployeePosition;

class OvertimeController extends Controller
{
    /**
     * Show the index page.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeOvertime::class);

        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        $recap = EmployeeDataRecapitulation::where('type', DataRecapitulationTypeEnum::OVERTIME)
        ->whereHas('employee', function($query){
            $query->where('grade_id', userGrades());
        })->whereStrictPeriodIn($start_at, $end_at)->get();

        $employees = Employee::with([
            'user',
            'position.position',
            'overtimes' => fn ($ov) => $ov->whereIn('id', $recap->pluck('result.overtimes.*.id')->flatten())
        ])
            ->where('grade_id', userGrades())
            ->whereIn('id', $recap->pluck('empl_id')->toArray())
            ->whenWithTrashed($request->get('trashed'))
            ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
            ->search($request->get('search'))
            ->paginate($request->get('limit', 10));

        return view('finance::report.overtimes.index', [
            ...compact('start_at', 'end_at'),
            'departments' => CompanyDepartment::visible()->with('positions')->get(),
            // 'employees' => Employee::with([
            //     'overtimes' => fn ($overtime) => $overtime->with('approvables')->whereExtractedDatesBetween($start_at, $end_at), 'user', 'position.position'
            // ])
            //     ->whereHas('overtimes', fn ($overtime) => $overtime->whereExtractedDatesBetween($start_at, $end_at))
            //     ->whenWithTrashed($request->get('trashed'))
            //     ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
            //     ->search($request->get('search'))
            //     ->paginate($request->get('limit', 10)),
            'employees' => $employees
        ]);
    }

    /**
     * Download excel recapitulations.
     */
    public function excel(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at') . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at') . ' 23:59:59');
        $poss = EmployeePosition::firstwhere('position_id', 3)->id;

        $period = $start_at->isSameDay($end_at) ? $end_at->isoFormat('DD MMM YYYY') : $start_at->isoFormat('DD MMM') . ' s.d. ' . $end_at->isoFormat('DD MMM YYYY');

        // $employees = Employee::with(['overtimes' => fn ($overtime) => $overtime->with('approvables')->whereExtractedDatesBetween($start_at, $end_at), 'user', 'position.position.department'])
        //     ->whereHas('overtimes', fn ($overtime) => $overtime->whereExtractedDatesBetween($start_at, $end_at))
        //     ->get();

        $employees = Employee::with([
            'user', 'position.position.department', 'position.position.parents',
            'overtimes' => fn ($overtime) => $overtime->with(['approvables'])
                ->whereHas('approvables', fn ($approve) => $approve->where('userable_id', $poss)->whereBetween('updated_at', [$start_at, $end_at]))
        ])
            ->where('grade_id', userGrades())
            ->wherehas('overtimes.approvables', fn ($approve) => $approve->where('userable_id', $poss)->whereBetween('updated_at', [$start_at, $end_at]))
            ->get();

        return response()->json([
            'title' => ($title = 'Rekap lembur karyawan periode ' . $period),
            'subtitle' => 'Diunduh pada ' . now()->isoFormat('LLLL'),
            'file' => Str::slug($title . '-' . time()),
            'columns' => [
                'index' => 'No',
                'name' => 'Nama',
                'department' => 'Departemen',
                'position' => 'Jabatan',
                'period_start' => 'Periode awal',
                'period_end' => 'Periode akhir',
                'total_hour' => 'Jumlah jam lembur',
                'paid_amount' => 'Lembur terbayar',
                'approved' => 'Disetujui',
                'total' => 'Total aktivitas lembur',
            ],
            'employees' => $employees->map(function ($employee, $index) use ($start_at, $end_at) {
                $overtimes = $employee->overtimes
                    ->filter(fn ($ov) => $ov->hasAllApprovableResultIn('APPROVE'))
                    ->unique('id')
                    ->flatMap(
                        fn ($overtime) => $overtime->dates->map(
                            fn ($date) => [
                                'date' => $date['d'],
                                'start_at' => ($start = Carbon::parse($date['d'] . ' ' . $date['t_s'])),
                                'end_at' => ($end = Carbon::parse($date['d'] . ' ' . $date['t_e'])),
                                'duration' => ($duration = round($start->diffInMinutes($end) / 60, 2)),
                                'rates' => ($rates = $employee->getOvertimeSalaryTemplate()),
                                'rate' => ($rate = $rates->where('start', '<=', $date['d'])->where('end', '>=', $date['d'])->first()['amount'] ?? $employee->getOvertimeSalaryViaActiveTemplate()),
                                'amount' => round($duration * $rate, 0)
                            ],
                        ),
                    )
                    // ->filter(fn ($overtime) => $end_at->lte($overtime['date'] . ' 23:59:59'));
                    ->filter(fn ($overtime) => $start_at->lte($overtime['date'] . ' 00:00:00') && $end_at->gte($overtime['date'] . ' 23:59:59'));

                $total_time = round($overtimes->map(fn ($overtime) => $overtime['start_at']->diffInMinutes($overtime['end_at']))->sum() / 60, 2);
                $total_amount = round($overtimes->map(fn ($overtime) => $overtime['amount'])->sum(), 2);

                return [
                    'index' => $index + 1,
                    'name' => $employee->user->name,
                    'department' => $employee->position->position->department->name,
                    'position' => $employee->position->position->name,
                    'period_start' => $start_at?->toDateString(),
                    'period_end' => $end_at?->toDateString(),
                    'total_hour' => $total_time,
                    'paid_amount' => round($employee->overtimes->sum('paid_amount')) ?: $total_amount,
                    'approved' => $employee->overtimes->filter(fn ($o) => $o->hasAllApprovableResultIn('APPROVE'))->count(),
                    'total' => $employee->overtimes->count(),
                ];
            })
        ]);
    }
}
