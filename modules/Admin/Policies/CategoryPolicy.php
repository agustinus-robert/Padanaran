<?php

namespace Modules\Admin\Policies;

use Modules\Account\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Models\Category;

class CategoryPolicy
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
        return $user->hasAnyPermissionsTo(['read-categories', 'write-categories', 'delete-categories']);
    }

    /**
     * Can show.
     */
    public function show(User $user, Category $model)
    {
        return $user->hasAnyPermissionsTo(['read-categories']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-categories']);
    }

    /**
     * Can update.
     */
    public function update(User $user, Category $model)
    {
        return $user->hasAnyPermissionsTo(['write-categories']);
    }


    /**
     * Can destroy.
     */
    public function destroy(User $user, Menu $model)
    {
        return $user->hasAnyPermissionsTo(['write-categories']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, Menu $model)
    {
        return $user->hasAnyPermissionsTo(['delete-categories']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, Menu $model)
    {
        return $user->hasAnyPermissionsTo(['delete-categories']);
    }
}
