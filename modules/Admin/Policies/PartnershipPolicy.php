<?php

namespace Modules\Admin\Policies;

use Modules\Account\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Models\Partnership;

class PartnershipPolicy
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
        return $user->hasAnyPermissionsTo(['read-partnerships', 'write-partnerships', 'delete-partnerships']);
    }

    /**
     * Can show.
     */
    public function show(User $user, Partnership $model)
    {
        return $user->hasAnyPermissionsTo(['read-partnerships']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-partnerships']);
    }

    /**
     * Can update.
     */
    public function update(User $user, Partnership $model)
    {
        return $user->hasAnyPermissionsTo(['write-partnerships']);
    }


    /**
     * Can destroy.
     */
    public function destroy(User $user, Partnership $model)
    {
        return $user->hasAnyPermissionsTo(['write-partnerships']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, Partnership $model)
    {
        return $user->hasAnyPermissionsTo(['delete-partnerships']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, Partnership $model)
    {
        return $user->hasAnyPermissionsTo(['delete-partnerships']);
    }
}
