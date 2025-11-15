<?php

namespace Modules\HRMS\Models;

use App\Models\Traits\Searchable\Searchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Modules\HRMS\Enums\WorkShiftEnum;

class EmployeeScheduleCategory extends Model
{
    use Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'empl_schedule_category';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'grade_id'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'name'      => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
