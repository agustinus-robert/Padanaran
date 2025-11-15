<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeSalaryTemplateItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeSalaryTemplateItemPolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-salary-template-items', 'write-employee-salary-template-items', 'delete-employee-salary-template-items']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeSalaryTemplateItem $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-salary-template-items']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-salary-template-items']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeSalaryTemplateItem $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-salary-template-items']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeSalaryTemplateItem $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-salary-template-items']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, EmployeeSalaryTemplateItem $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-salary-template-items']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, EmployeeSalaryTemplateItem $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-salary-template-items']);
    }
}
