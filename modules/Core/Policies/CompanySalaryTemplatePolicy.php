<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanySalaryTemplate;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanySalaryTemplatePolicy
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
        return $user->hasAnyPermissionsTo(['read-company-salary-templates', 'write-company-salary-templates', 'delete-company-salary-templates']);
    }

    /**
     * Can show.
     */
    public function show(User $user, CompanySalaryTemplate $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-salary-templates']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-salary-templates']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanySalaryTemplate $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-salary-templates']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanySalaryTemplate $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-salary-templates']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanySalaryTemplate $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-salary-templates']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanySalaryTemplate $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-salary-templates']);
    }
}
