<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeScanLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeScanLogPolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-scan-logs', 'write-employee-scan-logs', 'delete-employee-scan-logs']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeScanLog $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-scan-logs']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-scan-logs']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeScanLog $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-scan-logs']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeScanLog $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-scan-logs']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, EmployeeScanLog $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-scan-logs']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, EmployeeScanLog $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-scan-logs']);
    }
}