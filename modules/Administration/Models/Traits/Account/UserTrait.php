<?php

namespace Modules\Administration\Models\Traits\Account;

trait UserTrait
{
    /**
     * This belongs to many schools.
     */
    public function schools () {
        return $this->belongsToMany(\Modules\Administration\Models\School::class, 'sch_users', 'user_id', 'sch_id');
    }

    /**
     * Has instanced to school.
     */
    public function hasSchool () {
        return $this->schools()->exists();
    }

    /**
     * Get active school.
     */
    public function getActiveSchool () {
        return $this->schools()->wherePivot('active', 1)->first();
    }
}