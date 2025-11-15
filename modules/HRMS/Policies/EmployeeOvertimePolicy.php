<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeOvertime;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeOvertimePolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-overtimes', 'write-employee-overtimes', 'delete-employee-overtimes']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeOvertime $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-overtimes']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-overtimes']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeOvertime $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-overtimes']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeOvertime $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-overtimes']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, EmployeeOvertime $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-overtimes']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, EmployeeOvertime $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-overtimes']);
    }
}
