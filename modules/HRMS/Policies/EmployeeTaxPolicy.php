<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeTax;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeTaxPolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-taxs', 'write-employee-taxs', 'delete-employee-taxs']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeTax $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-taxs']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-taxs']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeTax $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-taxs']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeTax $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-taxs']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, EmployeeTax $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-taxs']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, EmployeeTax $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-taxs']);
    }
}
