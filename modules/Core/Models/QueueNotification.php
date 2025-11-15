<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class QueueNotification extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = "queue_notifications";

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'modelable_type', 'modelable_id',  'userable_type', 'userable_id', 'data', 'sent_at'
    ];

    protected $casts = [
        'data' => 'object',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'deleted_at', 'created_at', 'updated_at', 'sent_at'
    ];

    /**
     * Get the parent modelable model (employee vacations, etc.).
     */
    public function modelable()
    {
        return $this->morphTo();
    }

    /**
     * Get the parent userable model (employee vacations, etc.).
     */
    public function userable()
    {
        return $this->morphTo();
    }
}
