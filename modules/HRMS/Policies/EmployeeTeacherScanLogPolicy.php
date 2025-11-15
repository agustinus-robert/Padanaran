<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeTeacherScanLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeTeacherScanLogPolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-teacher-scan-logs', 'write-employee-teacher-scan-logs', 'delete-employee-teacher-scan-logs']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeTeacherScanLog $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-teacher-scan-logs']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-teacher-scan-logs']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeTeacherScanLog $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-teacher-scan-logs']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeTeacherScanLog $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-teacher-scan-logs']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, EmployeeTeacherScanLog $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-teacher-scan-logs']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, EmployeeTeacherScanLog $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-teacher-scan-logs']);
    }
}
