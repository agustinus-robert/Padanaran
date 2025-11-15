<?php

namespace Modules\Counseling\Policies;

use Modules\Account\Models\User;
use Modules\Academic\Models\StudentSemesterCase;
use Illuminate\Auth\Access\HandlesAuthorization;

class CounselingCaseCategoryPolicy
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
        return $user->hasAnyPermissionsTo(['read-counseling-case-categories', 'write-counseling-case-categories', 'delete-counseling-case-categories']);
    }

    /**
     * Can show.
     */
    public function show(User $user, StudentSemesterCase $model)
    {
        return $user->hasAnyPermissionsTo(['read-counseling-case-categories']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-counseling-case-categories']);
    }

    /**
     * Can update.
     */
    public function update(User $user, StudentSemesterCase $model)
    {
        return $user->hasAnyPermissionsTo(['write-counseling-case-categories']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, StudentSemesterCase $model)
    {
        return $user->hasAnyPermissionsTo(['write-counseling-case-categories']);
    }
}
