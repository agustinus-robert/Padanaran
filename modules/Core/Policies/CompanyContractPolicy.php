<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanyContract;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyContractPolicy
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
        return $user->hasAnyPermissionsTo(['read-company-contracts', 'write-company-contracts', 'delete-company-contracts']);
    }

    /**
     * Can show.
     */
    public function show(User $user, CompanyContract $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-contracts']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-contracts']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanyContract $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-contracts']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanyContract $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-contracts']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanyContract $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-contracts']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanyContract $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-contracts']);
    }
}
