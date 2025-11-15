<?php

namespace Modules\Admin\Policies;

use Modules\Account\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Models\OrderMenu;

class OrderMenuPolicy
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
        return $user->hasAnyPermissionsTo(['read-order-menus', 'write-order-menus', 'delete-order-menus']);
    }

    /**
     * Can show.
     */
    public function show(User $user, OrderMenu $model)
    {
        return $user->hasAnyPermissionsTo(['read-order-menus']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-order-menus']);
    }

    /**
     * Can update.
     */
    public function update(User $user, OrderMenu $model)
    {
        return $user->hasAnyPermissionsTo(['write-order-menus']);
    }


    /**
     * Can destroy.
     */
    public function destroy(User $user, OrderMenu $model)
    {
        return $user->hasAnyPermissionsTo(['write-order-menus']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, OrderMenu $model)
    {
        return $user->hasAnyPermissionsTo(['delete-order-menus']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, OrderMenu $model)
    {
        return $user->hasAnyPermissionsTo(['delete-order-menus']);
    }
}
