<?php

namespace Modules\Finance\Http\Controllers\Summary;

use Illuminate\Support\Arr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\Core\Models\CompanySalaryTemplate;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeDataRecapitulation;
use Modules\Finance\Http\Requests\Summary\Overtime\StoreRequest;
use Modules\HRMS\Models\EmployeeOvertime;

class OvertimeController extends Controller
{
    /**
     * Show the index page.
     */
    public function index(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        return view('finance::summary.overtimes.index', [
            'start_at'    => $start_at,
            'end_at'      => $end_at,
            'departments' => CompanyDepartment::where('grade_id', userGrades())->visible()->with('positions')->get(),
            'employees'   => Employee::with(['user', 'contract.position.position', 'dataRecapitulations' => fn($recap) => $recap->whereType(DataRecapitulationTypeEnum::OVERTIME)->whereStrictPeriodIn($start_at, $end_at)])
                ->withCount(['overtimes' => fn($overtime) => $overtime->whereBetween('paidable_at', [$start_at, $end_at])])
                ->where('grade_id', userGrades())
                ->whereHas('contract')
                ->whereHas('overtimes', fn($overtime) => $overtime->whereBetween('paidable_at', [$start_at, $end_at]))
                ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
                ->search($request->get('search'))
                ->paginate($request->get('limit', 10))
        ]);
    }

    /**
     * Create resource    
     */
    public function create(Request $request)
    {
        // return Employee::findOrFail($request->get('employee'))->getOvertimeSalaryTemplate();
        return view('finance::summary.overtimes.create', [
            'employee'  => ($employee = Employee::findOrFail($request->get('employee'))),
            'start_at'  => ($start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00')),
            'end_at'    => ($end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59')),
            'schedules' => ($schedules = $employee->schedules()->wherePeriodIn($start_at, $end_at)->pluck('dates')->flatMap(fn($v) => $v)),
            'recap'     => EmployeeDataRecapitulation::whereType(DataRecapitulationTypeEnum::OVERTIME)->find($request->get('edit')),
            'prices'    => $employee->getOvertimeSalaryTemplate(),
            'overtimes' => $employee->overtimes()->whereBetween('paidable_at', [$start_at, $end_at])->get()->each(function ($overtime) use ($schedules) {
                $overtime->dates = $overtime->dates->map(function ($date) use ($schedules) {
                    $date['s'] = Carbon::parse($date['d'] . ' ' . $date['t_s']);
                    $date['e'] = Carbon::parse($date['d'] . ' ' . $date['t_e']);
                    $minutes = $date['s']->diffInMinutes($date['e']);
                    return [...$date, ...compact('minutes')];
                });
                return $overtime;
            })
        ]);
    }

    /**
     * Store resource    
     */
    public function store(StoreRequest $request)
    {
        $employee = Employee::find($request->input('employee'));

        if ($current = EmployeeDataRecapitulation::whereType(DataRecapitulationTypeEnum::OVERTIME)->find($request->get('edit'))) {
            $employee->overtimes()->whereIn('id', array_column($current->result->overtimes, 'id'))->update([
                'paid_off_at' => null,
                'paid_amount' => null
            ]);
        }

        $recap = $employee->dataRecapitulations()->updateOrCreate(
            [
                'type' => DataRecapitulationTypeEnum::OVERTIME,
                ...$request->transformed()->only('start_at', 'end_at'),
            ],
            $request->transformed()->only('result')
        );

        $now = now();
        foreach ($request->transformed()->input('overtimes') as $overtime) {
            $employee->overtimes()->find($overtime['id'])->update([
                'paid_off_at' => $now,
                'paid_amount' => array_sum(array_column($overtime['dates'], 'total'))
            ]);
        }

        $request->user()->log(($current ? 'memperbarui' : 'membuat') . ' rekap ' . count($request->input('overtimes', [])) . ' lembur karyawan atas nama ' . $employee->user->name, EmployeeDataRecapitulation::class, $recap->id);
        return redirect()->next()->with('success', 'Rekap total <strong>' . count($request->input('overtimes', [])) . '</strong> lembur karyawan atas nama <strong>' . $employee->user->name . '</strong> berhasi ' . ($current ? 'diperbarui' : 'dibuat') . '!');
    }

    public function destroy(Employee $employee, Request $request)
    {
        $overtimes = explode(',', $request->get('overtimes'));
        $employee->dataRecapitulations()->whereType(DataRecapitulationTypeEnum::OVERTIME)->whereIn('id', $overtimes)->get()->each(
            fn($recap) => EmployeeOvertime::whereIn('id', array_column($recap->result->overtimes, 'id'))->update([
                'paid_off_at' => null,
                'paid_amount' => null
            ])
        );
        $employee->dataRecapitulations()->whereType(DataRecapitulationTypeEnum::OVERTIME)->whereIn('id', $overtimes)->delete();
        $request->user()->log('menghapus rekap ' . count($overtimes) . ' lembur karyawan atas nama ' . $employee->user->name, Employee::class, $employee->id);

        return redirect()->next()->with('success', 'Rekap total <strong>' . count($overtimes) . '</strong> lembur karyawan atas nama <strong>' . $employee->user->name . '</strong> berhasi dihapus!');
    }
}
