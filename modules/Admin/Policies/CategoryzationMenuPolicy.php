<?php

namespace Modules\Admin\Policies;

use Modules\Account\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Models\CategoryzationMenu;

class CategoryzationMenuPolicy
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
        return $user->hasAnyPermissionsTo(['read-categoryzation-menus', 'write-categoryzation-menus', 'delete-categoryzation-menus']);
    }

    /**
     * Can show.
     */
    public function show(User $user, CategoryzationMenu $model)
    {
        return $user->hasAnyPermissionsTo(['read-categoryzation-menus']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-categoryzation-menus']);
    }

    /**
     * Can update.
     */
    public function update(User $user, CategoryzationMenu $model)
    {
        return $user->hasAnyPermissionsTo(['write-categoryzation-menus']);
    }


    /**
     * Can destroy.
     */
    public function destroy(User $user, CategoryzationMenu $model)
    {
        return $user->hasAnyPermissionsTo(['write-categoryzation-menus']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, CategoryzationMenu $model)
    {
        return $user->hasAnyPermissionsTo(['delete-categoryzation-menus']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, CategoryzationMenu $model)
    {
        return $user->hasAnyPermissionsTo(['delete-categoryzation-menus']);
    }
}
