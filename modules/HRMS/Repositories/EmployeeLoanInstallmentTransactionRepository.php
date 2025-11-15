<?php

namespace Modules\HRMS\Repositories;

use Auth;
use Modules\HRMS\Models\EmployeeLoanInstallmentTransaction;

trait EmployeeLoanInstallmentTransactionRepository
{
    /**
     * Store newly created resource.
     */
    public function storeLoanTransaction(array $data)
    {
        $transaction = new EmployeeLoanInstallmentTransaction($data);

        if ($transaction->save()) {

            if ($transaction->amount >= $transaction->installment->amount) {
                $transaction->installment->update([
                    'paid_off_at' => $transaction->paid_at
                ]);
                if ($transaction->installment->loan->transactions->sum('amount') >= $transaction->installment->loan->amount_total && !isset($transaction->installment->loan->category->meta->multiplied_by_tenor)) {
                    $transaction->installment->loan->update([
                        'paid_at' => $transaction->paid_at
                    ]);
                }
            }

            Auth::user()->log('menambahkan pembayaran angsuran pinjaman karyawan ' . $transaction->installment->loan->employee->user->name . ' <strong>[ID: ' . $transaction->id . ']</strong>', EmployeeLoanInstallmentTransaction::class, $transaction->id);
            return $transaction;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyLoanTransaction(EmployeeLoanInstallmentTransaction $transaction)
    {
        if (!$transaction->trashed() && $transaction->delete()) {
            Auth::user()->log('menghapus pembayaran angsuran pinjaman karyawan ' . $transaction->installment->loan->employee->user->name . ' <strong>[ID: ' . $transaction->id . ']</strong>', EmployeeLoanInstallmentTransaction::class, $transaction->id);
            return $transaction;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreLoanTransaction(EmployeeLoanInstallmentTransaction $transaction)
    {
        if ($transaction->trashed() && $transaction->restore()) {
            Auth::user()->log('memulihkan pembayaran angsuran pinjaman karyawan ' . $transaction->installment->loan->employee->user->name . ' <strong>[ID: ' . $transaction->id . ']</strong>', EmployeeLoanInstallmentTransaction::class, $transaction->id);
            return $transaction;
        }
        return false;
    }
}
