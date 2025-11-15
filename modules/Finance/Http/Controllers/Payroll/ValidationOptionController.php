<?php

namespace Modules\Finance\Http\Controllers\Payroll;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Account\Models\User;
use Modules\Finance\Http\Controllers\Controller;
use Modules\Finance\Notifications\Payroll\Validation\DirectorNotification;
use Modules\Finance\Notifications\Payroll\Validation\EmployeeNotification;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeDataRecapitulation;
use Modules\HRMS\Models\EmployeeReimbursement;
use Modules\HRMS\Models\EmployeeSalary;

class ValidationOptionController extends Controller
{
    /**
     * Send notify to Director.
     */
    public function notifyToDirector(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at'));
        $end_at = Carbon::parse($request->get('end_at'));

        User::find(config('modules.core.features.services.salaries.validation_notify'))->notify(new DirectorNotification($start_at, $end_at));

        return redirect()->next()->with('success', 'Notifikasi persetujuan gaji ke surel Direktur berhasil dikirim!');
    }

    /**
     * Release validated salaries based periods.
     */
    public function release(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at'));
        $end_at = Carbon::parse($request->get('end_at'));

        $period = $start_at->isSameDay($end_at) ? $end_at->isoFormat('DD MMM YYYY') : $start_at->isoFormat('DD MMM') . ' s.d. ' . $end_at->isoFormat('DD MMM YYYY');

        EmployeeSalary::whereDate('start_at', $start_at->format('Y-m-d'))->whereDate('end_at', $end_at->format('Y-m-d'))->whereNotNull('validated_at')->whereNull('released_at')->update([
            'released_at' => now()
        ]);

        // Update some services when recaps
        $reimbursements = EmployeeDataRecapitulation::whereType(DataRecapitulationTypeEnum::REIMBURSEMENT)->whereStrictPeriodIn($start_at->format('Y-m-d'), $end_at->format('Y-m-d'))->get()->pluck('result.reimbursements')->flatten(1);
        if (count($reimbursements)) {
            EmployeeReimbursement::whereNull('paid_at')->whereIn('id', $reimbursements->pluck('id'))->update(['paid_at' => $end_at]);
        }

        // Send notification via email

        return redirect()->next()->with('success', 'Seluruh gaji periode <strong>' . $period . '</strong> berhasil dirilis, terima kasih!');
    }

    /**
     * Send notify to Employees about released salaries.
     */
    public function notifyToEmployees(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at'));
        $end_at = Carbon::parse($request->get('end_at'));
        $period = $start_at->isSameDay($end_at) ? $end_at->isoFormat('DD MMM YYYY') : $start_at->isoFormat('DD MMM') . ' s.d. ' . $end_at->isoFormat('DD MMM YYYY');
        $employees = Employee::with('user')->withWhereHas('salaries', fn ($salary) => $salary->whereDate('start_at', $start_at->format('Y-m-d'))->whereDate('end_at', $end_at->format('Y-m-d'))->whereNotNull('validated_at')->whereNotNull('released_at'))->get();

        foreach ($employees as $key => $employee) {
            $employee->user->notify(new EmployeeNotification($employee->salaries->first(), $key * 5));
        }

        return redirect()->next()->with('success', 'Notifikasi persetujuan gaji periode <strong>' . $period . '</strong> yang sudah rilis ke total <strong>' . $employees->count() . ' karyawan</strong> berhasil dikirim!');
    }
}
