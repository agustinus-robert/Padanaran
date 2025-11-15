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

class LoanTransactionListController extends Controller
{
    use EmployeeLoanInstallmentTransactionRepository;

    /* *
     * Show all resource
     */
    public function index(Request $request)
    {
        return view('finance::service.loans.transactions.index', [
            'methods'       => LoanMethodEnum::cases(),
            'cashs'         => LoanCashTypeEnum::cases(),
            'transactions'  => EmployeeLoanInstallmentTransaction::with(['installment.loan' => fn ($item) => $item->with('employee', 'category')])
                ->search($request->get('search'))
                ->whenTrashed($request->get('trash'))
                ->orderByDesc('paid_at')
                ->paginate($request->get('limit', 10))
        ]);
    }

    public function destroy(EmployeeLoanInstallmentTransaction $transaction)
    {
        if ($transcation = $this->destroyLoanTransaction($transaction)) {
            return redirect()->next()->with('success', 'Pembayaran pinjaman karyawan dengan nama <strong>' . $transcation->installment->loan->employee->user->name . '</strong> berhasil dihapus dari sistem');
        }
        return redirect()->fail();
    }

    public function restore(EmployeeLoanInstallmentTransaction $transaction)
    {
        if ($transcation = $this->restoreLoanTransaction($transaction)) {
            return redirect()->next()->with('success', 'Pembayaran pinjaman karyawan dengan nama <strong>' . $transcation->installment->loan->employee->user->name . '</strong> berhasil dipulihkan');
        }
        return redirect()->fail();
    }
}
