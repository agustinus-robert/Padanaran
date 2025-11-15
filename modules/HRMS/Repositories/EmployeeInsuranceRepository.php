<?php

namespace Modules\HRMS\Repositories;

use Illuminate\Support\Facades\Auth;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeInsurance;

trait EmployeeInsuranceRepository
{
    /**
     * Store newly created resource.
     */
    public function storeEmployeeInsurance(Employee $employee, array $data)
    {
        $insuranceData = array_merge($data, [
            'grade_id' => userGrades(),
        ]);

        if ($insurance = $employee->insurances()->save(new EmployeeInsurance($insuranceData))) {
            Auth::user()->log('menambahkan asuransi karyawan ' . $insurance->employee->user->name . ' <strong>[ID: ' . $insurance->id . ']</strong>', EmployeeInsurance::class, $insurance->id);
            return $insurance;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyInsurance(EmployeeInsurance $insurance)
    {
        if (!$insurance->trashed() && $insurance->delete()) {
            Auth::user()->log('menghapus asuransi karyawan ' . $insurance->employee->user->name . ' <strong>[ID: ' . $insurance->id . ']</strong>', EmployeeInsurance::class, $insurance->id);
            return $insurance;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreInsurance(EmployeeInsurance $insurance)
    {
        if ($insurance->trashed() && $insurance->restore()) {
            Auth::user()->log('memulihkan asuransi karyawan ' . $insurance->employee->user->name . ' <strong>[ID: ' . $insurance->id . ']</strong>', EmployeeInsurance::class, $insurance->id);
            return $insurance;
        }
        return false;
    }
}
