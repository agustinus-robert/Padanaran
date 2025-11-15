<?php

namespace Modules\Core\Models\Traits\Approvable;

use Modules\Core\Models\CompanyApprovable;

trait Approver
{
    /**
     * Get all of the approver.
     */
    public function approver()
    {
        return $this->morphMany(CompanyApprovable::class, 'userable');
    }

    /**
     * Get approver label.
     */
    public function getApproverLabel()
    {
        return data_get($this, $this->approver_label);
    }

    /**
     * Get user instance.
     */
    public function getUser()
    {
        return data_get($this, $this->approver_user);
    }
}
