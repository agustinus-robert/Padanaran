<?php

namespace Modules\Boarding\Models\Traits\Approvable;

use Modules\Boarding\Models\BoardingCompanyApprovable;

trait Approver
{
    /**
     * Get all of the approver.
     */
    public function approver()
    {
        return $this->morphMany(BoardingCompanyApprovable::class, 'userable');
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
