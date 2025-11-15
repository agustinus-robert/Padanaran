<?php

namespace Modules\Portal\Policies;

use Modules\Account\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Models\Category;

class UserPortalPolicy
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
        return $user->hasAnyPermissionsTo(['read-portal','write-portal','delete-portal']);
    }

}
