<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeSalaryTemplate;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeSalaryTemplatePolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-salary-templates', 'write-employee-salary-templates', 'delete-employee-salary-templates']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeSalaryTemplate $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-salary-templates']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-salary-templates']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeSalaryTemplate $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-salary-templates']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeSalaryTemplate $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-salary-templates']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, EmployeeSalaryTemplate $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-salary-templates']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, EmployeeSalaryTemplate $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-salary-templates']);
    }
}
