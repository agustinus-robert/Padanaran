<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanyLoanCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyLoanCategoryPolicy
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
        return $user->hasAnyPermissionsTo(['read-company-loan-categories', 'write-company-loan-categories', 'delete-company-loan-categories']);
    }

    /**
     * Can show.
     * Can show.
     */
    public function show(User $user, CompanyLoanCategory $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-loan-categories']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-loan-categories']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanyLoanCategory $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-loan-categories']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanyLoanCategory $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-loan-categories']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanyLoanCategory $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-loan-categories']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanyLoanCategory $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-loan-categories']);
    }
}
