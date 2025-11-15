<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanyInsurance;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyInsurancePolicy
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
        return $user->hasAnyPermissionsTo(['read-company-insurances', 'write-company-insurances', 'delete-company-insurances']);
    }

    /**
     * Can show.
     */
    public function show(User $user, CompanyInsurance $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-insurances']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-insurances']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanyInsurance $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-insurances']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanyInsurance $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-insurances']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanyInsurance $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-insurances']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanyInsurance $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-insurances']);
    }
}
