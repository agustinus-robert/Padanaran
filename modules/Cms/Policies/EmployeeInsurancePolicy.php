<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeInsurance;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeInsurancePolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-insurances', 'write-employee-insurances', 'delete-employee-insurances']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeInsurance $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-insurances']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-insurances']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeInsurance $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-insurances']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeInsurance $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-insurances']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, EmployeeInsurance $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-insurances']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, EmployeeInsurance $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-insurances']);
    }
}