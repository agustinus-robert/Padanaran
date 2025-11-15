<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanyUnit;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyUnitPolicy
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
        return $user->hasAnyPermissionsTo(['read-company-units', 'write-company-units', 'delete-company-units']);
    }

    /**
     * Can show.
     */
    public function show(User $user, CompanyUnit $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-units']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-units']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanyUnit $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-units']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanyUnit $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-units']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanyUnit $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-units']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanyUnit $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-units']);
    }
}
