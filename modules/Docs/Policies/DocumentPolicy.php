<?php

namespace Modules\Docs\Policies;

use Modules\Account\Models\User;
use Modules\Docs\Models\Document;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
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
        return $user->hasAnyPermissionsTo(['read-documents', 'write-documents', 'delete-documents']);
    }

    /**
     * Can show.
     */
    public function show(User $user, Document $model)
    {
        return $user->hasAnyPermissionsTo(['read-documents']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-documents']);
    }

    /**
     * Can update.
     */
    public function update(User $user, Document $model)
    {
        return $user->hasAnyPermissionsTo(['write-documents']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, Document $model)
    {
        return $user->hasAnyPermissionsTo(['write-documents']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, Document $model)
    {
        return $user->hasAnyPermissionsTo(['delete-documents']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, Document $model)
    {
        return $user->hasAnyPermissionsTo(['delete-documents']);
    }
}
