<?php

namespace Modules\Finance\Http\Controllers\Payroll;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Enums\SalaryUnitEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Http\Controllers\Payroll\CalculationController;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeSalary;

class ValidationController extends CalculationController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeSalary::class);

        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        return view('finance::payroll.validations.index', [
            ...compact('start_at', 'end_at'),
            'departments' => CompanyDepartment::visible()->with('positions')->get(),
            'hasApprovedSalaries' => EmployeeSalary::whereDate('start_at', $start_at->format('Y-m-d'))->whereDate('end_at', $end_at->format('Y-m-d'))->whereNotNull('approved_at')->count(),
            'hasReleasedSalaries' => EmployeeSalary::whereDate('start_at', $start_at->format('Y-m-d'))->whereDate('end_at', $end_at->format('Y-m-d'))->whereNotNull('released_at')->count(),
            'employees' => Employee::with([
                'salaries' => fn ($salary) => $salary->whereDate('start_at', $start_at->format('Y-m-d'))->whereDate('end_at', $end_at->format('Y-m-d')),
                'user', 'position.position'
            ])
                ->where('grade_id', userGrades())
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
        return view('finance::payroll.validations.show', [
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
            'validated_at' => $request->has('validated') ? now() : null
        ]);

        return redirect()->next()->with('success', 'Validasi untuk gaji <strong>' . $salary->name . '</strong> untuk <strong>' . $salary->employee->name . '</strong> berhasil diperbarui!');
    }

    /**
     * Send notify to Directors about notify.
     */
    public function notify()
    {
        return redirect()->next()->with('success', 'Notifikasi persetujuan gaji ke surel Direktur berhasil dikirim!');
    }
}
