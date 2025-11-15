<?php

namespace Modules\Finance\Http\Controllers\Service\Loan;

use Illuminate\Http\Request;
use Modules\Core\Enums\LoanTenorEnum;
use Modules\Core\Enums\PositionLevelEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\Core\Models\CompanyLoanCategory;
use Modules\Finance\Http\Controllers\Controller;
use Modules\Finance\Http\Requests\Service\Loan\StoreRequest;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeLoan;
use Modules\HRMS\Models\EmployeeLoanInstallment;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start_at = $request->get('start_at', date('Y-01-01')) . ' 00:00:00';
        $end_at = $request->get('end_at', date('Y-m-t')) . ' 23:59:59';

        $departments = CompanyDepartment::visible()->with('positions')->get();

        $loans = EmployeeLoan::with('employee.user', 'employee.position', 'transactions')
            ->withCount(['installments' => fn ($installment) => $installment->whereNotNull('paid_off_at')])
            ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
            ->isApproved()
            ->paidOff($request->get('paidoff'))
            // ->inPayment()
            ->latest()
            ->search($request->get('search'))
            ->whenTrashed($request->get('trash'))
            ->paginate($request->get('limit', 10));

        $unpaid_loans_count = EmployeeLoan::whereNull('paid_at')->count();

        return view('finance::service.loans.index', compact('loans', 'unpaid_loans_count', 'start_at', 'end_at', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = CompanyLoanCategory::orderBy('type')->get()->sortby('meta.az');
        $tenor_types = LoanTenorEnum::cases();

        return view('finance::service.loans.create', compact('categories', 'tenor_types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $employee = Employee::find($request->input('employee'));
        $request = $request->transformed();

        $data = ['description', 'tenor', 'tenor_by', 'submission_at', 'start_at', 'empl_id', 'amount_total', 'ctg_id', 'approved_at'];

        if ($loan = $employee->loans()->create($request->only($data))) {
            if ($request->input('interest')) {
                if ($interest = $employee->loans()->create($request->input('interest'))) {
                    $interest->installments()->createMany($request->input('interest.installments'));
                }
            }
            if ($loan->installments()->createMany($request->input('installments'))) {
                return redirect()->next()->with('success', 'Pinjaman karyawan baru dengan nama <strong>' . $loan->employee->user->name . '</strong> berhasil dibuat dengan nominal <strong>Rp ' . \Str::money($loan->amount_total) . '</strong>');
            }
        }
        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, EmployeeLoan $loan)
    {
        $loan = $loan->load('employee.user', 'installments.transactions', 'transactions');

        $parent = $loan->employee->position->position->parents
            ->whereIn('level', [PositionLevelEnum::MANAGER, PositionLevelEnum::DIRECTOR])
            ->whereIn('dept_id', [$loan->employee->position->position->dept_id, 3])
            ->last()?->employees->first()->user->name;

        return view('finance::service.loans.show', compact('loan', 'parent'));
    }

    /**
     * Toggle paid_off_at.
     */
    public function togglePaid(EmployeeLoan $loan)
    {
        $loan->update(['paid_at' => isset($loan->paid_at) ? null : now()]);

        return redirect()->back()->with('success', 'Pinjaman karyawan baru dengan nama <strong>' . $loan->employee->user->name . '</strong> sudah ditandai sebagai ' . ($loan->paid_at ? 'belum' : '') . ' lunas');;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeLoan $loan)
    {
        if ($loan->delete()) {
            return redirect()->next()->with('success', 'Pinjaman karyawan baru dengan nama <strong>' . $loan->employee->user->name . '</strong> berhasil dihapus.');
        }
        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(EmployeeLoan $loan)
    {
        if ($loan->restore()) {
            return redirect()->next()->with('success', 'Pinjaman karyawan baru dengan nama <strong>' . $loan->employee->user->name . '</strong> berhasil dipulihkan.');
        }
        return redirect()->fail();
    }
}
