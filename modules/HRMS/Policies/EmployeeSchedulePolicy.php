<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeSchedule;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeSchedulePolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-schedules', 'write-employee-schedules', 'delete-employee-schedules']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeSchedule $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-schedules']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-schedules']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeSchedule $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-schedules']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeSchedule $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-schedules']);
    }
}
