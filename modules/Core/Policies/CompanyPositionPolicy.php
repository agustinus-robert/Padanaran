<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanyPosition;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPositionPolicy
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
        return $user->hasAnyPermissionsTo(['read-company-positions', 'write-company-positions', 'delete-company-positions']);
    }

    /**
     * Can show.
     */
    public function show(User $user, CompanyPosition $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-positions']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-positions']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanyPosition $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-positions']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanyPosition $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-positions']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanyPosition $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-positions']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanyPosition $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-positions']);
    }
}
