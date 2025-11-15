<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanyPayrollSetting;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPayrollSettingPolicy
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
        return $user->hasAnyPermissionsTo(['read-company-payroll-settings', 'write-company-payroll-settings', 'delete-company-payroll-settings']);
    }

    /**
     * Can show.
     */
    public function show(User $user, CompanyPayrollSetting $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-payroll-settings']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-payroll-settings']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanyPayrollSetting $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-payroll-settings']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanyPayrollSetting $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-payroll-settings']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanyPayrollSetting $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-payroll-settings']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanyPayrollSetting $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-payroll-settings']);
    }
}
