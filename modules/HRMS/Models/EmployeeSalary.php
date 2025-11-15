<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Modules\Core\Models\CompanySalary;
use Modules\Docs\Models\Traits\Documentable\Documentable;

class EmployeeSalary extends Model
{
    use Restorable, Searchable, Documentable;

    /**
     * The table associated with the model.
     */
    protected $table = 'empl_salaries';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'empl_id', 'name', 'start_at', 'end_at', 'components', 'amount', 'file', 'validated_at', 'approved_at', 'released_at', 'accepted_at', 'complain', 'description'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'components' => 'collection',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'start_at'   => 'datetime',
        'end_at'     => 'datetime',
        'validated_at' => 'datetime',
        'approved_at' => 'datetime',
        'released_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'start_at', 'end_at', 'validated_at', 'approved_at', 'released_at', 'accepted_at', 'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'name', 'employee.user.name'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * This belongs to employee.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'empl_id')->withDefault()->withTrashed();
    }

    /**
     * Scope when period.
     */
    public function scopeWhenPeriod($query, $start_at, $end_at = null)
    {
        return $query->when(
            isset($start_at) && isset($end_at),
            fn ($subquery) => $subquery->whereDate('start_at', '>=', $start_at)->whereDate('end_at', '<=', date('Y-m-t', strtotime($end_at)))
        );
    }

    /**
     * Scope when year period.
     */
    public function scopeWhenYearPeriod($query, $start_at, $end_at = null)
    {
        return $query->when(
            isset($start_at) && isset($end_at),
            fn ($subquery) => $subquery->whereYear('start_at', date('Y', strtotime($start_at)))->whereYear('end_at', date('Y', strtotime($end_at)))
        );
    }
}
