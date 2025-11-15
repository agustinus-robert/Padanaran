<?php

namespace Modules\Admin\Policies;

use Modules\Account\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Models\SubNews;

class SubNewsPolicy
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
        return $user->hasAnyPermissionsTo(['read-sub-news', 'write-sub-news', 'delete-sub-news']);
    }

    /**
     * Can show.
     */
    public function show(User $user, SubNews $model)
    {
        return $user->hasAnyPermissionsTo(['read-sub-news']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-sub-news']);
    }

    /**
     * Can update.
     */
    public function update(User $user, SubNews $model)
    {
        return $user->hasAnyPermissionsTo(['write-sub-news']);
    }


    /**
     * Can destroy.
     */
    public function destroy(User $user, SubNews $model)
    {
        return $user->hasAnyPermissionsTo(['write-sub-news']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, SubNews $model)
    {
        return $user->hasAnyPermissionsTo(['delete-sub-news']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, SubNews $model)
    {
        return $user->hasAnyPermissionsTo(['delete-sub-news']);
    }
}
