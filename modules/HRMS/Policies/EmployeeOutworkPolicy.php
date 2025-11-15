<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeOutwork;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeOutworkPolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-outworks', 'write-employee-outworks', 'delete-employee-outworks']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeOutwork $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-outworks']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-outworks']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeOutwork $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-outworks']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeOutwork $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-outworks']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, EmployeeOutwork $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-outworks']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, EmployeeOutwork $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-outworks']);
    }
}
