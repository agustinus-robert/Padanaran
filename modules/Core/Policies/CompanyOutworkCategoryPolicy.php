<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanyOutworkCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyOutworkCategoryPolicy
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
        return $user->hasAnyPermissionsTo(['read-company-outwork-categories', 'write-company-outwork-categories', 'delete-company-outwork-categories']);
    }

    /**
     * Can show.
     * Can show.
     */
    public function show(User $user, CompanyOutworkCategory $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-outwork-categories']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-outwork-categories']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanyOutworkCategory $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-outwork-categories']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanyOutworkCategory $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-outwork-categories']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanyOutworkCategory $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-outwork-categories']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanyOutworkCategory $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-outwork-categories']);
    }
}
