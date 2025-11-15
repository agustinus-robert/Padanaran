<?php

namespace Modules\Admin\Policies;

use Modules\Account\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Models\Tags;

class TagsPolicy
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
        return $user->hasAnyPermissionsTo(['read-tags', 'write-tags', 'delete-tags']);
    }

    /**
     * Can show.
     */
    public function show(User $user, Tags $model)
    {
        return $user->hasAnyPermissionsTo(['read-tags']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-tags']);
    }

    /**
     * Can update.
     */
    public function update(User $user, Tags $model)
    {
        return $user->hasAnyPermissionsTo(['write-tags']);
    }


    /**
     * Can destroy.
     */
    public function destroy(User $user, Tags $model)
    {
        return $user->hasAnyPermissionsTo(['write-tags']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, Tags $model)
    {
        return $user->hasAnyPermissionsTo(['delete-tags']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, Tags $model)
    {
        return $user->hasAnyPermissionsTo(['delete-tags']);
    }
}
