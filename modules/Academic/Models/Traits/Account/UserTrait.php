<?php

namespace Modules\Academic\Models\Traits\Account;

trait UserTrait
{
    /**
     * This hasMany student
     */
    public function student () {
        return $this->hasOne(\Modules\Academic\Models\Student::class, 'user_id');
    }

    /**
     * Is user is student
     */
    public function isStudent()
    {
        return $this->student()->exists();
    }

    /**
     * This hasOne employee
     */
    public function employee () {
        return $this->hasOne(\Modules\HRMS\Models\Employee::class, 'user_id');
    }

    /**
     * Is user is employee
     */
    public function isEmployee()
    {
        return $this->employee()->exists();
    }

    /**
     * This hasMany teacher
     */
    public function teacher () {
        return $this->hasOneThrough(
            \Modules\Academic\Models\EmployeeTeacher::class,
            \Modules\Academic\Models\Employee::class,
            'user_id',
            'employee_id'
        );
    }

    /**
     * Is user is teacher
     */
    public function isTeacher()
    {
        return $this->teacher()->exists();
    }

    /**
     * Is user is counselor
     */
    public function isCounselor()
    {
        return $this->hasPermissions(['manage-cases', 'counsel-students']);
    }
}
