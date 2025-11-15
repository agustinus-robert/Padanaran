<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeVacation;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeVacationPolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-vacations', 'write-employee-vacations', 'delete-employee-vacations']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeVacation $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-vacations']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-vacations']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeVacation $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-vacations']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeVacation $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-vacations']);
    }
}
