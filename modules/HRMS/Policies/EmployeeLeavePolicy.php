<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeLeave;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeLeavePolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-leaves', 'write-employee-leaves', 'delete-employee-leaves']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeLeave $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-leaves']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-leaves']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeLeave $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-leaves']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeLeave $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-leaves']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, EmployeeLeave $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-leaves']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, EmployeeLeave $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-leaves']);
    }
}
