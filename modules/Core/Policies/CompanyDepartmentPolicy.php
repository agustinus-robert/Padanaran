<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanyDepartment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyDepartmentPolicy
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
        return $user->hasAnyPermissionsTo(['read-company-departments', 'write-company-departments', 'delete-company-departments']);
    }

    /**
     * Can show.
     */
    public function show(User $user, CompanyDepartment $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-departments']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-departments']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanyDepartment $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-departments']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanyDepartment $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-departments']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanyDepartment $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-departments']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanyDepartment $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-departments']);
    }
}
