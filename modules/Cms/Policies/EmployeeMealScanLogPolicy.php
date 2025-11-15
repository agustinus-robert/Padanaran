<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeMealScanLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeMealScanLogPolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-meal-scan-logs', 'write-employee-meal-scan-logs', 'delete-employee-meal-scan-logs']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeMealScanLog $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-meal-scan-logs']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-meal-scan-logs']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeMealScanLog $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-meal-scan-logs']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeMealScanLog $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-meal-scan-logs']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, EmployeeMealScanLog $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-meal-scan-logs']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, EmployeeMealScanLog $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-meal-scan-logs']);
    }
}
