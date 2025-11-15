<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeVacationQuota;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeVacationQuotaPolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-vacation-quotas', 'write-employee-vacation-quotas', 'delete-employee-vacation-quotas']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeVacationQuota $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-vacation-quotas']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-vacation-quotas']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeVacationQuota $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-vacation-quotas']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeVacationQuota $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-vacation-quotas']);
    }
}