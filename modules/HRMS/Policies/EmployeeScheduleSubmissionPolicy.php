<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeScheduleSubmission;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeScheduleSubmissionPolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-schedules-submissions', 'write-employee-schedules-submissions', 'delete-employee-schedules-submissions']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeScheduleSubmission $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-schedules-submissions']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-schedules-submissions']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeScheduleSubmission $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-schedules-submissions']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeScheduleSubmission $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-schedules-submissions']);
    }
}
