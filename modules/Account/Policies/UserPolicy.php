<?php

namespace Modules\Account\Policies;

use Modules\Account\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
        return $user->hasAnyPermissionsTo(['read-users', 'write-users', 'delete-users', 'cross-login-users']);
    }

    /**
     * Can access freelancer.
     */
    public function accessFreelancers(User $user)
    {
        return $user->hasAnyPermissionsTo(['read-freelancers']);
    }

    /**
     * Can show.
     */
    public function show(User $user, User $model)
    {
        return $user->hasAnyPermissionsTo(['read-users']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-users']);
    }

    /**
     * Can update.
     */
    public function update(User $user, User $model)
    {
        return $user->hasAnyPermissionsTo(['write-users']) || ($user->id === $model->id && !$model->trashed());
    }

    /**
     * Can cross login.
     */
    public function crossLogin(User $user, User $model)
    {
        return $user->hasAnyPermissionsTo(['cross-login-users']) && !$model->trashed();
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, User $model)
    {
        return $user->hasAnyPermissionsTo(['write-users']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, User $model)
    {
        return $user->hasAnyPermissionsTo(['delete-users']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, User $model)
    {
        return $user->hasAnyPermissionsTo(['delete-users']);
    }
}
