<?php

namespace Modules\HRMS\Models;

use App\Models\Traits\Searchable\Searchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Modules\HRMS\Enums\WorkShiftEnum;

class EmployeeScheduleLesson extends Model
{
    use Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'empl_schedules_lesson';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'category_schedule_id',
        'name',
        'start_at',
        'end_at',
        'grade_id'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'start_at'   => 'date',
        'end_at'     => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'start_at',
        'end_at',
        'created_at',
        'updated_at'
    ];
}
