<?php

namespace Modules\Admin\Policies;

use Modules\Account\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Models\CareerData;

class CareerDataPolicy
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
        return $user->hasAnyPermissionsTo(['read-careers', 'write-careers', 'delete-careers']);
    }

    /**
     * Can show.
     */
    public function show(User $user, CareerData $model)
    {
        return $user->hasAnyPermissionsTo(['read-careers']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-careers']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CareerData $model)
    {
        return $user->hasAnyPermissionsTo(['write-careers']);
    }


    /**
     * Can destroy.
     */
    public function destroy(User $user, CareerData $model)
    {
        return $user->hasAnyPermissionsTo(['write-careers']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CareerData $model)
    {
        return $user->hasAnyPermissionsTo(['delete-careers']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CareerData $model)
    {
        return $user->hasAnyPermissionsTo(['delete-careers']);
    }
}
