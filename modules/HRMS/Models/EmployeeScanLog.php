<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Searchable\Searchable;
use App\Models\Casts\UserAgent\UserAgentCast;

class EmployeeScanLog extends Model
{
    use Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'empl_scan_logs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'empl_id',
        'ip',
        'location',
        'latlong',
        'user_agent',
        'created_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'latlong' => 'array',
        'user_agent' => UserAgentCast::class,
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'employee.user.name',
        'ip',
        'user_agent'
    ];

    /**
     * This belongs to employee.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'empl_id', 'id');
    }

    /**
     * When position of department.
     */
    public function scopeWhenPositionOfDepartment($query, $dep, $pos)
    {
        return $query->when(
            $dep,
            fn($q1) =>
            $q1->whereHas('employee.contract.position.position', fn($q3) => $q3->whereIn('dept_id', (array) $dep))->when(
                $pos,
                fn($q2) =>
                $q2->whereHas('employee.contract.position', fn($q3) => $q3->whereIn('position_id', (array) $pos))
            )
        );
    }
}
