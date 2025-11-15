<?php

namespace Modules\HRMS\Policies;

use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeRecapSubmission;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeRecapSubmissionPolicy
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
        return $user->hasAnyPermissionsTo(['read-employee-recap-submissions', 'write-employee-recap-submissions', 'delete-employee-recap-submissions']);
    }

    /**
     * Can show.
     */
    public function show(User $user, EmployeeRecapSubmission $model)
    {
        return $user->hasAnyPermissionsTo(['read-employee-recap-submissions']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-employee-recap-submissions']);
    }

    /**
     * Can update.
     */
    public function update(User $user, EmployeeRecapSubmission $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-recap-submissions']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, EmployeeRecapSubmission $model)
    {
        return $user->hasAnyPermissionsTo(['write-employee-recap-submissions']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, EmployeeRecapSubmission $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-recap-submissions']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, EmployeeRecapSubmission $model)
    {
        return $user->hasAnyPermissionsTo(['delete-employee-recap-submissions']);
    }
}
