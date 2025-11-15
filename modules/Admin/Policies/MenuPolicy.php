<?php

namespace Modules\Admin\Policies;

use Modules\Account\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Models\Menu;

class MenuPolicy
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
        return $user->hasAnyPermissionsTo(['read-menus', 'write-menus', 'delete-menus']);
    }

    /**
     * Can show.
     */
    public function show(User $user, Menu $model)
    {
        return $user->hasAnyPermissionsTo(['read-menus']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-menus']);
    }

    /**
     * Can update.
     */
    public function update(User $user, Menu $model)
    {
        return $user->hasAnyPermissionsTo(['write-menus']);
    }


    /**
     * Can destroy.
     */
    public function destroy(User $user, Menu $model)
    {
        return $user->hasAnyPermissionsTo(['write-menus']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, Menu $model)
    {
        return $user->hasAnyPermissionsTo(['delete-menus']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, Menu $model)
    {
        return $user->hasAnyPermissionsTo(['delete-menus']);
    }
}
