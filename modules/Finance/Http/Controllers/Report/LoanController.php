<?php

namespace Modules\Finance\Http\Controllers\Report;

use Carbon\Carbon;
use Modules\Core\Models\CompanyDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeLoan;
use Modules\HRMS\Models\EmployeeLoanInstallment;

class LoanController extends Controller
{
    /**
     * Show the index page.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeLoan::class);

        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        return view('finance::report.loans.index', [
            ...compact('start_at', 'end_at'),
            'departments' => CompanyDepartment::visible()->with('positions')->get(),
            'installments' => EmployeeLoanInstallment::with(['loan.installments', 'loan.category', 'loan.employee' => fn ($e) => $e->with(['user', 'position.position'])])
                ->where(
                    fn ($q) => $q->whereBetween('bill_at', [$start_at, $end_at])->orWHereBetween('paid_off_at', [$start_at, $end_at])
                )
                ->whereHas('loan.employee', fn ($e) => $e->whenPositionOfDepartment($request->get('department'), $request->get('position')))
                ->whenWithTrashed($request->get('trashed'))
                ->search($request->get('search'))
                ->paginate($request->get('limit', 10)),
        ]);
    }

    /**
     * Download excel recapitulations.
     */
    public function excel(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at') . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at') . ' 23:59:59');

        $period = $start_at->isSameDay($end_at) ? $end_at->isoFormat('DD MMM YYYY') : $start_at->isoFormat('DD MMM') . ' s.d. ' . $end_at->isoFormat('DD MMM YYYY');

        $employees = Employee::with(['overtimes' => fn ($overtime) => $overtime->with('approvables')->whereExtractedDatesBetween($start_at, $end_at), 'user', 'position.position.department'])
            ->whereHas('overtimes', fn ($overtime) => $overtime->whereExtractedDatesBetween($start_at, $end_at))
            ->get();

        return response()->json([
            'title' => ($title = 'Rekap pinjaman karyawan periode ' . $period),
            'subtitle' => 'Diunduh pada ' . now()->isoFormat('LLLL'),
            'file' => Str::slug($title . '-' . time()),
            'columns' => [
                'index' => 'No',
                'name' => 'Nama',
                'department' => 'Departemen',
                'position' => 'Jabatan',
                'loan_ctg' => 'Kategori pinjaman',
                'loan_desc' => 'Nama',
                'loan_amount' => 'Total pinjaman',
                'loan_paid_amount' => 'Terbayar',
                'tenor' => 'Tenor',
                'installment_index' => 'Tagihan ke',
                'installment_billed_at' => 'Tgl tagihan',
                'installment_amount' => 'Tagihan dibayar',
                'installment_paid_at' => 'Tgl pelunasan tagihan',
                'submitted_at' => 'Tgl pengajuan pinjaman',
                'paid_at' => 'Tgl pelunasan pinjaman'
            ],
            'installments' => EmployeeLoanInstallment::with(['loan.installments.transactions', 'loan.category', 'loan.employee' => fn ($e) => $e->with(['user', 'position.position'])])
                ->where(fn ($q) => $q->whereBetween('bill_at', [$start_at, $end_at])->orWHereBetween('paid_off_at', [$start_at, $end_at]))
                ->whereHas('loan.employee', fn ($e) => $e->whenPositionOfDepartment($request->get('department'), $request->get('position')))->get()->sortBy('loan.empl_id')->values()->map(function ($installment, $index) {
                    return [
                        'index' => $index + 1,
                        'name' => $installment->loan->employee->user->name,
                        'department' => $installment->loan->employee->position->position->department->name,
                        'position' => $installment->loan->employee->position->position->name,
                        'loan_ctg' => $installment->loan->category->name,
                        'loan_desc' => $installment->loan->description,
                        'loan_amount' => $installment->loan->amount_total,
                        'loan_paid_amount' => $installment->loan->installments->pluck('transactions')->flatten(1)->sum('amount'),
                        'tenor' => $installment->loan->tenor . ' ' . $installment->loan->tenor_by->label(),
                        'installment_index' => $installment->loan->installments->map(fn ($inst, $i) => $installment->is($inst) ? $i : -1)->filter(fn ($o) => $o >= 0)->first() + 1,
                        'installment_billed_at' => $installment->bill_at?->toDateString(),
                        'installment_amount' => $installment->amount,
                        'installment_paid_at' => $installment->paid_off_at?->toDateTimeString(),
                        'submitted_at' => $installment->loan->submission_at?->toDateTimeString(),
                        'paid_at' => $installment->loan->paid_at?->toDateTimeString()
                    ];
                })
        ]);
    }
}
