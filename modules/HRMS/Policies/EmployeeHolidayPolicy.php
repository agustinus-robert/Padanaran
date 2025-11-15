<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeHoliday;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeHolidayPolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-holidays', 'write-employee-holidays', 'delete-employee-holidays']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeHoliday $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-holidays']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-holidays']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeHoliday $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-holidays']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeHoliday $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-holidays']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, EmployeeHoliday $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-holidays']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, EmployeeHoliday $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-holidays']);
    }
}
