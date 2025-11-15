<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanyApprovable;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyApprovablePolicy
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
        return $user->hasAnyPermissionsTo(['read-company-approvables', 'write-company-approvables', 'delete-company-approvables']);
    }

    /**
     * Can show.
     */
    public function show(User $user, CompanyApprovable $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-approvables']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-approvables']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanyApprovable $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-approvables']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanyApprovable $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-approvables']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanyApprovable $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-approvables']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanyApprovable $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-approvables']);
    }
}
