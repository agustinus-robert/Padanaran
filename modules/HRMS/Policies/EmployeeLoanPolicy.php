<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeLoan;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeLoanPolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-loans', 'write-employee-loans', 'delete-employee-loans']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeLoan $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-loans']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-loans']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeLoan $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-loans']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeLoan $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-loans']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, EmployeeLoan $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-loans']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, EmployeeLoan $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-loans']);
    }
}
