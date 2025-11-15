<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeDataRecapitulation;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeDataRecapitulationPolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-data-recapitulations', 'write-employee-data-recapitulations', 'delete-employee-data-recapitulations']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeDataRecapitulation $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-data-recapitulations']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-data-recapitulations']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeDataRecapitulation $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-data-recapitulations']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeDataRecapitulation $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-data-recapitulations']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, EmployeeDataRecapitulation $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-data-recapitulations']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, EmployeeDataRecapitulation $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-data-recapitulations']);
    }
}
