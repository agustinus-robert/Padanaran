<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanyMoment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyMomentPolicy
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
        return $user->hasAnyPermissionsTo(['read-company-moments', 'write-company-moments', 'delete-company-moments']);
    }

    /**
     * Can show.
     * Can show.
     */
    public function show(User $user, CompanyMoment $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-moments']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-moments']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanyMoment $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-moments']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanyMoment $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-moments']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanyMoment $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-moments']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanyMoment $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-moments']);
    }
}
