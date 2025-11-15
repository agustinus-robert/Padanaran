<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;

class EmployeeDataRecapitulation extends Model
{
    use Restorable, Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'empl_data_recaps';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'empl_id',
        'type',
        'start_at',
        'end_at',
        'result',
        'created_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'type'       => DataRecapitulationTypeEnum::class,
        'result'     => 'object',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'start_at'   => 'datetime',
        'end_at'     => 'datetime'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'start_at',
        'end_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'employee.user.name'
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
     * Scope where strict period in.
     */
    public function scopeWhereStrictPeriodIn($query, $start_at, $end_at)
    {
        return $query->where('start_at', date('Y-m-d', strtotime($start_at)))->where('end_at', date('Y-m-d', strtotime($end_at)));
    }

    /**
     * Get is active attribute.
     */
    public function getIsActiveAttribute()
    {
        $now = now();
        return (bool) ($this->start_at <= $now) && ($this->end_at >= $now || is_null($this->end_at));
    }

    /**
     * Scope active.
     */
    public function scopeActive($query)
    {
        $now = now();
        return $query->where('start_at', '<=', $now)->where(fn($subquery) => $subquery->where('end_at', '>=', $now)->orWhereNull('end_at'));
    }
}
