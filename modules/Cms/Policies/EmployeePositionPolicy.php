<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeePosition;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePositionPolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-positions', 'write-employee-positions', 'delete-employee-positions']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeePosition $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-positions']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-positions']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeePosition $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-positions']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeePosition $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-positions']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, EmployeePosition $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-positions']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, EmployeePosition $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-positions']);
    }
}