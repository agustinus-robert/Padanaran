<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyVacationCategory;

class EmployeeVacationQuota extends Model
{
    use Restorable, Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'empl_vacation_quotas';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'empl_id', 'start_at', 'end_at', 'ctg_id', 'quota', 'visible_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'start_at'   => 'datetime',
        'end_at'     => 'datetime',
        'visible_at' => 'datetime',
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'start_at', 'end_at', 'visible_at', 'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'kd', 'description'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'is_active'
    ];

    /**
     * Get is active attribute.
     */
    public function getIsActiveAttribute()
    {
        $now = now();
        return ($this->start_at <= $now) && ($this->end_at >= $now || is_null('end_at'));
    }

    /**
     * Get remaining quota attribute.
     */
    public function getRemainAttribute()
    {
        return $this->relationLoaded('vacations')
            ? $this->quota - $this->vacations->filter(fn ($v) => $v->hasAllApprovableResultIn('APPROVE') || $v->approvables->where('cancelable', true)->count() == $v->approvables->count())->sum(fn ($v) => count($v->dates))
            : 0;
    }

    /**
     * This belongs to employee.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'empl_id')->withDefault()->withTrashed();
    }

    /**
     * This belongs to category.
     */
    public function category()
    {
        return $this->belongsTo(CompanyVacationCategory::class, 'ctg_id')->withDefault()->withTrashed();
    }

    /**
     * This has many vacations.
     */
    public function vacations()
    {
        return $this->hasMany(EmployeeVacation::class, 'quota_id');
    }

    /**
     * Scope where in period.
     */
    public function scopeWhenInPeriod($query, $start_at = false, $end_at = false)
    {
        return $query->when($start_at, fn ($subquery) => $subquery->where('start_at', '>=', $start_at))
            ->when($end_at, fn ($subquery) => $subquery->where('end_at', '<=', $end_at));
    }

    /**
     * Scope where in year.
     */
    public function scopeWhenInYear($query, $year)
    {
        return $query->when($year, fn ($subquery) => $subquery->whereYear('start_at', $year))
            ->when($year, fn ($subquery) => $subquery->orWhereYear('end_at', $year));
    }

    /**
     * Scope active.
     */
    public function scopeActive($query)
    {
        $now = now();
        // return $query->where('start_at', '<=', $now)->where(fn ($subquery) => $subquery->where('end_at', '>=', $now)->orWhereNull('end_at'));
        return $query->where(fn ($sub) => $sub->where('start_at', '<=', $now)->orwhere('visible_at', '<=', $now))->where(fn ($subquery) => $subquery->where('end_at', '>=', $now)->orWhereNull('end_at'));
    }
}
