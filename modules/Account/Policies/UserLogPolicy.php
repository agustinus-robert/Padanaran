<?php

namespace Modules\Account\Policies;

use Modules\Account\Models\User;
use Modules\Account\Models\UserLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserLogPolicy
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
        return $user->hasAnyPermissionsTo(['read-user-logs', 'delete-user-logs']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, UserLog $model)
    {
        return $user->hasAnyPermissionsTo(['delete-user-logs']);
    }
}