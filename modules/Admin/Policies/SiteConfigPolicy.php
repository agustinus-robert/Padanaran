<?php

namespace Modules\Admin\Policies;

use Modules\Account\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Models\SiteConfig;

class SiteConfigPolicy
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
        return $user->hasAnyPermissionsTo(['read-site-configs', 'write-site-configs', 'delete-site-configs']);
    }

    /**
     * Can show.
     */
    public function show(User $user, SiteConfig $model)
    {
        return $user->hasAnyPermissionsTo(['read-site-configs']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-site-configs']);
    }

    /**
     * Can update.
     */
    public function update(User $user, SiteConfig $model)
    {
        return $user->hasAnyPermissionsTo(['write-site-configs']);
    }


    /**
     * Can destroy.
     */
    public function destroy(User $user, SiteConfig $model)
    {
        return $user->hasAnyPermissionsTo(['write-site-configs']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, SiteConfig $model)
    {
        return $user->hasAnyPermissionsTo(['delete-site-configs']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, SiteConfig $model)
    {
        return $user->hasAnyPermissionsTo(['delete-site-configs']);
    }
}
