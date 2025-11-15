<?php

namespace Modules\Admin\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Repositories\UserRepository;
use Modules\Account\Repositories\User\PhoneRepository;
use Modules\Admin\Repositories\EmployeeContractRepository;
use Modules\Account\Models\Employee;

trait EmployeeRepository
{
    use UserRepository, PhoneRepository, EmployeeContractRepository;

    /**
     * Store newly created resource.
     */
    public function storeEmployee(array $data)
    {
        $user = $this->storeUser(Arr::only($data, ['name', 'username', 'password']));
        $this->updatePhone($user, Arr::only($data, ['phone_code', 'phone_number', 'phone_whatsapp']));

        $employee = new Employee(Arr::only($data, ['joined_at']));

        if ($user->employee()->save($employee)) {

            if (!isset($data['contract'])) {
                $this->storeEmployeeContract($employee, Arr::only($data, ['contract_id', 'kd', 'start_at', 'end_at', 'contract_file', 'work_location']));
            }

            Auth::user()->log('menambahkan karyawan baru dengan nama ' . $user->name . ' <strong>[ID: ' . $employee->id . ']</strong>', Employee::class, $employee->id);
            return $employee;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateEmployee(Employee $employee, array $data)
    {
        if ($employee->fill(Arr::only($data, ['joined_at', 'permanent_at', 'kd', 'permanent_kd', 'permanent_sk']))->save()) {
            Auth::user()->log('memperbarui data karyawan baru dengan nama ' . $employee->user->name . ' <strong>[ID: ' . $employee->id . ']</strong>', Employee::class, $employee->id);
            return $employee;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyEmployee(Employee $employee)
    {
        if (!$employee->trashed() && $employee->delete()) {
            Auth::user()->log('menghapus karyawan ' . $employee->user->name . ' <strong>[ID: ' . $employee->id . ']</strong>', Employee::class, $employee->id);
            return $employee;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreEmployee(Employee $employee)
    {
        if ($employee->trashed() && $employee->restore()) {
            Auth::user()->log('memulihkan karyawan ' . $employee->user->name . ' <strong>[ID: ' . $employee->id . ']</strong>', Employee::class, $employee->id);
            return $employee;
        }
        return false;
    }
}
