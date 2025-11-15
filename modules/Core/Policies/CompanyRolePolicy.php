<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanyRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyRolePolicy
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
        return $user->hasAnyPermissionsTo(['read-company-roles', 'write-company-roles', 'delete-company-roles']);
    }

    /**
     * Can show.
     */
    public function show(User $user, CompanyRole $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-roles']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-roles']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanyRole $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-roles']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanyRole $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-roles']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanyRole $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-roles']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanyRole $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-roles']);
    }
}
