<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeSalary;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeSalaryPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }

    /**
     * Can access page.
     */
    public function access(User $user)
    {
        return $user->hasAnyPermissionsTo(['read-employee-salaries', 'write-employee-salaries', 'delete-employee-salaries']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeSalary $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-salaries']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-salaries']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeSalary $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-salaries']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeSalary $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-salaries']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, EmployeeSalary $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-salaries']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, EmployeeSalary $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-salaries']);
    }
}
