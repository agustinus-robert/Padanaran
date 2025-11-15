<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanyBuilding;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyBuildingPolicy
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
        return $user->hasAnyPermissionsTo(['read-company-buildings', 'write-company-buildings', 'delete-company-buildings']);
    }

    /**
     * Can show.
     */
    public function show(User $user, CompanyBuilding $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-buildings']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-buildings']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanyBuilding $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-buildings']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanyBuilding $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-buildings']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanyBuilding $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-buildings']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanyBuilding $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-buildings']);
    }
}
