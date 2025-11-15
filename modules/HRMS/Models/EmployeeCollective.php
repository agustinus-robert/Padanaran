<?php

namespace Modules\HRMS\Models;

use App\Models\Traits\Searchable\Searchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Modules\HRMS\Enums\WorkShiftEnum;

class EmployeeCollective extends Model
{
    use Searchable;

    /**
     * The table associated with the model.
     */
    public $timestamps = false;
    protected $table = 'empl_collective';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'date',
        'type',
        'data',
    ];
}
