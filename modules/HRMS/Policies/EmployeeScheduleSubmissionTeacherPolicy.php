<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeScheduleSubmissionTeacher;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeScheduleSubmissionTeacherPolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-schedule-submission-teachers', 'write-employee-schedule-submission-teachers', 'delete-employee-schedule-submission-teachers']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeScheduleSubmissionTeacher $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-schedule-submission-teachers']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-schedule-submission-teachers']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeScheduleSubmissionTeacher $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-schedule-submission-teachers']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeScheduleSubmissionTeacher $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-schedule-submission-teachers']);
    }
}
