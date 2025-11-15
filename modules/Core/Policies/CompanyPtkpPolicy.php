<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanyPtkp;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPtkpPolicy
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
        return $user->hasAnyPermissionsTo(['read-company-ptkps', 'write-company-ptkps', 'delete-company-ptkps']);
    }

    /**
     * Can show.
     */
    public function show(User $user, CompanyPtkp $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-ptkps']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-ptkps']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanyPtkp $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-ptkps']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanyPtkp $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-ptkps']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanyPtkp $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-ptkps']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanyPtkp $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-ptkps']);
    }
}
