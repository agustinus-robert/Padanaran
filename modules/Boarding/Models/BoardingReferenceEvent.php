<?php

namespace Modules\Boarding\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academic\Models\Student;
use Modules\Administration\Models\SchoolBuilding;
use Modules\Boarding\Enums\BoardingEventTypeEnum;

class BoardingReferenceEvent extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'sch_boarding_event';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'type',
        'start_date',
        'end_date',
        'in',
        'out',
        'type_participant',
        'grade_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'type' => BoardingEventTypeEnum::class,
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();
        return $this->withTrashed()->where($field, $value)->first();
    }
}
