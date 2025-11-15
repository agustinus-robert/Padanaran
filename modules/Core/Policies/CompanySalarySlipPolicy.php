<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanySalarySlip;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanySalarySlipPolicy
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
        return $user->hasAnyPermissionsTo(['read-company-salary-slips', 'write-company-salary-slips', 'delete-company-salary-slips']);
    }

    /**
     * Can show.
     */
    public function show(User $user, CompanySalarySlip $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-salary-slips']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-salary-slips']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanySalarySlip $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-salary-slips']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanySalarySlip $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-salary-slips']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanySalarySlip $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-salary-slips']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanySalarySlip $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-salary-slips']);
    }
}
