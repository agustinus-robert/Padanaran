<?php

namespace Modules\Finance\Http\Controllers\Report;

use Carbon\Carbon;
use Modules\Core\Models\CompanyDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Core\Models\CompanySalarySlipComponent;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeSalary;

class SalaryController extends Controller
{
    /**
     * Show the index page.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeSalary::class);

        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        return view('finance::report.salaries.index', [
            ...compact('start_at', 'end_at'),
            'departments' => CompanyDepartment::visible()->where('grade_id', userGrades())->with('positions')->get(),
            'hasApprovedSalaries' => EmployeeSalary::whereDate('start_at', $start_at->format('Y-m-d'))->whereDate('end_at', $end_at->format('Y-m-d'))->whereNotNull('approved_at')->count(),
            'hasReleasedSalaries' => EmployeeSalary::whereDate('start_at', $start_at->format('Y-m-d'))->whereDate('end_at', $end_at->format('Y-m-d'))->whereNotNull('released_at')->count(),
            'employees' => Employee::with(['salaries' => fn ($salary) => $salary->whereDate('start_at', $start_at->format('Y-m-d'))->whereDate('end_at', $end_at->format('Y-m-d')), 'user', 'position.position'])
                ->whereHas('salaries', fn ($salary) => $salary->whereDate('start_at', $start_at->format('Y-m-d'))->whereDate('end_at', $end_at->format('Y-m-d')))
                ->where('grade_id', userGrades())
                ->whenWithTrashed($request->get('trashed'))
                ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
                ->search($request->get('search'))
                ->paginate($request->get('limit', 10)),
        ]);
    }

    /**
     * Download excel recapitulations.
     */
    public function excel(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at'));
        $end_at = Carbon::parse($request->get('end_at'));

        $period = $start_at->isSameDay($end_at) ? $end_at->isoFormat('DD MMM YYYY') : $start_at->isoFormat('DD MMM') . ' s.d. ' . $end_at->isoFormat('DD MMM YYYY');

        $employees = Employee::with('user.meta')
            ->with([
                'position.position.department',
                'dataRecapitulations' => fn ($salary) => $salary->whereType(DataRecapitulationTypeEnum::ATTENDANCE)->whereDate('start_at', $start_at->format('Y-m-d'))->whereDate('end_at', $end_at->format('Y-m-d')),
                'salaries' => fn ($salary) => $salary->whereDate('start_at', $start_at->format('Y-m-d'))->whereDate('end_at', $end_at->format('Y-m-d'))
            ])
            ->where('grade_id', userGrades())
            ->whereHas('salaries', fn ($salary) => $salary->whereDate('start_at', $start_at->format('Y-m-d'))->whereDate('end_at', $end_at->format('Y-m-d')))->get();

        $component_ids = $employees->pluck('salaries.*.components.*.ctgs.*.i.*.component_id')->flatten()->unique()->sort()->values();
        $components = CompanySalarySlipComponent::with('slip', 'category')->where('grade_id', userGrades())->whereIn('id', $component_ids)->get(['id', 'slip_id', 'ctg_id', 'name'])->groupBy(['slip.name', 'category.name']);
        $attendances = config('modules.core.features.recapitulations.attendances');

        return response()->json([
            'title' => ($title = 'Rekap gaji karyawan periode ' . $period),
            'subtitle' => 'Diunduh pada ' . now()->isoFormat('LLLL'),
            'file' => Str::slug($title . '-' . time()),
            'components' => $components,
            'attendances' => $attendances,
            'employees' => $employees->map(function ($employee) use ($components, $attendances) {
                $salary = $employee->salaries->first();
                $items = $salary->components->pluck('ctgs.*.i')->flatten(2)->pluck('amount', 'component_id')->toArray();
                $attendance = $employee->dataRecapitulations->first();
                return [
                    'name' => $employee->user->name,
                    'npwp' => $employee->user->getMeta('tax_number'),
                    'department' => $employee->position->position->department->name,
                    'position' => $employee->position->position->name,
                    'attendances' => array_map(fn ($key) => $attendance?->result ? data_get($attendance->result, $key) : 0, Arr::flatten($attendances)),
                    'components' => $components->flatten()->map(fn ($component) => $items[$component->id] ?? 0),
                    'total' => $salary->amount
                ];
            })
        ]);
    }
}
