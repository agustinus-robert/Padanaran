<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanySalarySlipComponent;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanySalarySlipComponentPolicy
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
        return $user->hasAnyPermissionsTo(['read-company-salary-slip-components', 'write-company-salary-slip-components', 'delete-company-salary-slip-components']);
    }

    /**
     * Can show.
     */
    public function show(User $user, CompanySalarySlipComponent $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-salary-slip-components']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-salary-slip-components']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanySalarySlipComponent $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-salary-slip-components']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanySalarySlipComponent $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-salary-slip-components']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanySalarySlipComponent $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-salary-slip-components']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanySalarySlipComponent $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-salary-slip-components']);
    }
}
