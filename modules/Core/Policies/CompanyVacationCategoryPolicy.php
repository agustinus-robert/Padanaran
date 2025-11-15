<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanyVacationCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyVacationCategoryPolicy
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
        return $user->hasAnyPermissionsTo(['read-company-vacation-categories', 'write-company-vacation-categories', 'delete-company-vacation-categories']);
    }

    /**
     * Can show.
     * Can show.
     */
    public function show(User $user, CompanyVacationCategory $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-vacation-categories']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-vacation-categories']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanyVacationCategory $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-vacation-categories']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanyVacationCategory $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-vacation-categories']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanyVacationCategory $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-vacation-categories']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanyVacationCategory $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-vacation-categories']);
    }
}
