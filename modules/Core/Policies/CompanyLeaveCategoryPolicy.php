<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanyLeaveCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyLeaveCategoryPolicy
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
        return $user->hasAnyPermissionsTo(['read-company-leave-categories', 'write-company-leave-categories', 'delete-company-leave-categories']);
    }

    /**
     * Can show.
     * Can show.
     */
    public function show(User $user, CompanyLeaveCategory $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-leave-categories']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-leave-categories']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanyLeaveCategory $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-leave-categories']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanyLeaveCategory $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-leave-categories']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanyLeaveCategory $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-leave-categories']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanyLeaveCategory $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-leave-categories']);
    }
}
