<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeContractSchedule;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeContractSchedulePolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-contract-schedules', 'write-employee-contract-schedules', 'delete-employee-contract-schedules']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeContractSchedule $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-contract-schedules']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-contract-schedules']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeContractSchedule $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-contract-schedules']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeContractSchedule $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-contract-schedules']);
    }
}