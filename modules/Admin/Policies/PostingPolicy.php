<?php

namespace Modules\Admin\Policies;

use Modules\Account\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Models\Post;

class PostingPolicy
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
        return $user->hasAnyPermissionsTo(['read-postings', 'write-postings', 'delete-postings']);
    }

    /**
     * Can show.
     */
    public function show(User $user, Post $model)
    {
        return $user->hasAnyPermissionsTo(['read-postings']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-postings']);
    }

    /**
     * Can update.
     */
    public function update(User $user, Post $model)
    {
        return $user->hasAnyPermissionsTo(['write-postings']);
    }


    /**
     * Can destroy.
     */
    public function destroy(User $user, Post $model)
    {
        return $user->hasAnyPermissionsTo(['write-postings']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, Post $model)
    {
        return $user->hasAnyPermissionsTo(['delete-postings']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, Post $model)
    {
        return $user->hasAnyPermissionsTo(['delete-postings']);
    }
}
