<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeScheduleTeacher;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeScheduleTeacherPolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-schedule-teachers', 'write-employee-schedule-teachers', 'delete-employee-schedule-teachers']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeScheduleTeacher $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-schedule-teachers']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-schedule-teachers']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeScheduleTeacher $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-schedule-teachers']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeScheduleTeacher $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-schedule-teachers']);
    }
}
