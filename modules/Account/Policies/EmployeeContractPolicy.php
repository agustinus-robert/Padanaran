<?php

namespace Modules\Account\Policies;

use Modules\Account\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Account\Models\EmployeeContract;

class EmployeeContractPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        // 
    }

    /**
     * Can access page.
     */
    public function access(User $user)
    {
        return $user->hasAnyPermissionsTo(['read-employee-contracts', 'write-employee-contracts', 'delete-employee-contracts']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeContract $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-contracts']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-contracts']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeContract $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-contracts']);
    }


    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeContract $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-contracts']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, EmployeeContract $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-contracts']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, EmployeeContract $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-contracts']);
    }
}
