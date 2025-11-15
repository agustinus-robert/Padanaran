<?php

namespace Modules\Admin\Policies;

use Modules\Account\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Models\Contact;

class ContactPolicy
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
        return $user->hasAnyPermissionsTo(['read-contacts', 'write-contacts', 'delete-contacts']);
    }

    /**
     * Can show.
     */
    public function show(User $user, Contact $model)
    {
        return $user->hasAnyPermissionsTo(['read-contacts']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-contacts']);
    }

    /**
     * Can update.
     */
    public function update(User $user, Contact $model)
    {
        return $user->hasAnyPermissionsTo(['write-contacts']);
    }


    /**
     * Can destroy.
     */
    public function destroy(User $user, Contact $model)
    {
        return $user->hasAnyPermissionsTo(['write-contacts']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, Contact $model)
    {
        return $user->hasAnyPermissionsTo(['delete-contacts']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, Contact $model)
    {
        return $user->hasAnyPermissionsTo(['delete-contacts']);
    }
}
