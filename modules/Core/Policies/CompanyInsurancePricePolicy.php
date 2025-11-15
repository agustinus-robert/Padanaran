<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanyInsurancePrice;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyInsurancePricePolicy
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
        return $user->hasAnyPermissionsTo(['read-company-insurance-prices', 'write-company-insurance-prices', 'delete-company-insurance-prices']);
    }

    /**
     * Can show.
     */
    public function show(User $user, CompanyInsurancePrice $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-insurance-prices']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-insurance-prices']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanyInsurancePrice $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-insurance-prices']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanyInsurancePrice $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-insurance-prices']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanyInsurancePrice $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-insurance-prices']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanyInsurancePrice $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-insurance-prices']);
    }
}
