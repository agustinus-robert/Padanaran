<?php

namespace Modules\Core\Policies;

use Modules\Account\Models\User;
use Modules\Core\Models\CompanyStudentLeaveCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyStudentLeaveCategoryPolicy
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
        return $user->hasAnyPermissionsTo(['read-company-student-leave-categories', 'write-company-student-leave-categories', 'delete-company-leave-categories']);
    }

    /**
     * Can show.
     * Can show.
     */
    public function show(User $user, CompanyStudentLeaveCategory $model)
    {
        return $user->hasAnyPermissionsTo(['read-company-student-leave-categories']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-company-student-leave-categories']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CompanyStudentLeaveCategory $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-student-leave-categories']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, CompanyStudentLeaveCategory $model)
    {
        return $user->hasAnyPermissionsTo(['write-company-student-leave-categories']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CompanyStudentLeaveCategory $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-student-leave-categories']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CompanyStudentLeaveCategory $model)
    {
        return $user->hasAnyPermissionsTo(['delete-company-student-leave-categories']);
    }
}
