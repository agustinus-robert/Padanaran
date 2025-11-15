<?php

namespace Modules\Core\Models\Traits;

use Modules\Core\Models\QueueNotification;

trait Queueable
{
    /**
     * Get all of the notifiable.
     */
    public function notifiables()
    {
        return $this->morphMany(QueueNotification::class, 'modelable');
    }
}
