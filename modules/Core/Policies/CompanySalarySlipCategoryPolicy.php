<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanySalarySlipCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanySalarySlipCategoryPolicy
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
        return $user->hasAnyPermissionsTo(['read-company-salary-slip-categories', 'write-company-salary-slip-categories', 'delete-company-salary-slip-categories']);
    }

    /**
     * Can show.
     */
    public function show(User $user, CompanySalarySlipCategory $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-salary-slip-categories']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-salary-slip-categories']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanySalarySlipCategory $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-salary-slip-categories']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanySalarySlipCategory $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-salary-slip-categories']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanySalarySlipCategory $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-salary-slip-categories']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanySalarySlipCategory $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-salary-slip-categories']);
    }
}
