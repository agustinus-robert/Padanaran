<?php

namespace Modules\Finance\Http\Controllers\Service\Loan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Finance\Http\Controllers\Controller;
use Modules\HRMS\Models\EmployeeLoan;
use Modules\HRMS\Enums\LoanMethodEnum;
use Modules\HRMS\Enums\LoanCashTypeEnum;
use Modules\Finance\Http\Requests\Service\Loan\Transaction\StoreRequest;
use Modules\HRMS\Models\EmployeeLoanInstallmentTransaction;
use Modules\HRMS\Repositories\EmployeeLoanInstallmentTransactionRepository;

class LoanTransactionController extends Controller
{
    use EmployeeLoanInstallmentTransactionRepository;

    /**
     * Show the form for creating a new resource.
     */
    public function create(EmployeeLoan $loan, Request $request)
    {
        return view('finance::service.loans.transactions.create', [
            'user'        => Auth::user(),
            'methods'     => LoanMethodEnum::cases(),
            'cashs'       => LoanCashTypeEnum::cases(),
            'loan'        => $loan->load('employee.user', 'installments', 'transactions'),
            'number'      => ($number = $request->get('installment', ($loan->installments->whereNull('paid_off_at')->first()->id))),
            'installment' => $loan->installments->where('id', $number)->first(),
        ]);
    }

    /**
     * store a new resource.
     */
    public function store(StoreRequest $request)
    {
        if ($transcation = $this->storeLoanTransaction($request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Pembayaran pinjaman karyawan dengan nama <strong>' . $transcation->installment->loan->employee->user->name . '</strong> berhasil dibuat dengan nominal <strong>Rp ' . \Str::money($transcation->amount) . '</strong>');
        }
        return redirect()->fail();
    }
}
