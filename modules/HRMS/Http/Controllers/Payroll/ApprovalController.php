<?php

namespace Modules\HRMS\Http\Controllers\Payroll;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Enums\SalaryUnitEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeSalary;

class ApprovalController extends CalculationController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeSalary::class);

        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');


        return view('hrms::payroll.approvals.index', [
            ...compact('start_at', 'end_at'),
            'departments' => CompanyDepartment::where('grade_id', userGrades())->visible()->with('positions')->get(),
            'hasValidatedSalaries' => EmployeeSalary::whereHas('employee', function($query){
                $query->where('grade_id', userGrades());
            })->whereDate('start_at', $start_at->format('Y-m-d'))->whereDate('end_at', $end_at->format('Y-m-d'))->whereNotNull('validated_at')->count(),
            'hasReleasedSalaries' => EmployeeSalary::whereHas('employee', function ($query) {
                $query->where('grade_id', userGrades());
            })->whereDate('start_at', $start_at->format('Y-m-d'))->whereDate('end_at', $end_at->format('Y-m-d'))->whereNotNull('released_at')->count(),
            'employees' => Employee::where('grade_id', userGrades())->with(['salaries' => fn($salary) => $salary->whereDate('start_at', $start_at->format('Y-m-d'))->whereDate('end_at', $end_at->format('Y-m-d')), 'user', 'position.position'])
                ->whenWithTrashed($request->get('trashed'))
                ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
                ->isActive()
                ->search($request->get('search'))
                ->paginate($request->get('limit', 10)),
        ]);
    }

    /**
     * Show the form for editing the current resource.
     */
    public function edit(EmployeeSalary $salary, Request $request)
    {
        return view('hrms::payroll.approvals.edit', [
            ...compact('salary'),
            'units' => SalaryUnitEnum::cases(),
            'employee' => $salary->employee->load('position')
        ]);
    }

    /**
     * Update validations.
     */
    public function update(EmployeeSalary $salary, Request $request)
    {
        $salary->update([
            'approved_at' => $request->has('approved') ? now() : null
        ]);

        return redirect()->next()->with('success', 'Persetujuan untuk gaji <strong>' . $salary->name . '</strong> untuk <strong>' . $salary->employee->name . '</strong> berhasil disetujui!');
    }
}
