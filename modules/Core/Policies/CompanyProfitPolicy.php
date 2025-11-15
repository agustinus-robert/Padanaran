<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanyProfit;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyProfitPolicy
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
        return $user->hasAnyPermissionsTo(['read-company-profits', 'write-company-profits', 'delete-company-profits']);
    }

    /**
     * Can show.
     */
    public function show(User $user, CompanyProfit $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-profits']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-profits']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanyProfit $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-profits']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanyProfit $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-profits']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanyProfit $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-profits']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanyProfit $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-profits']);
    }
}
