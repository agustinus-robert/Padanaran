<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeDeduction;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeDeductionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    /**
     * Can access page.
     */
    public function access(User $user)
    {
        return $user->hasAnyPermissionsTo(['read-employee-deductions', 'write-employee-deductions', 'delete-employee-deductions']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeDeduction $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-deductions']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-deductions']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeDeduction $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-deductions']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeDeduction $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-deductions']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, EmployeeDeduction $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-deductions']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, EmployeeDeduction $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-deductions']);
    }
}
