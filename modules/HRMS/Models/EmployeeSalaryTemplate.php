<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Modules\Core\Models\CompanySalary;
use Modules\Core\Models\CompanySalarySlipComponent;
use Modules\Core\Models\CompanySalaryTemplate;

class EmployeeSalaryTemplate extends Model
{
    use Restorable, Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'empl_salary_templates';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'empl_id',
        'cmp_template_id',
        'name',
        'prefix',
        'start_at',
        'end_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
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
        'name',
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
     * This belongs to companyTemplate.
     */
    public function companyTemplate()
    {
        return $this->belongsTo(CompanySalaryTemplate::class, 'cmp_template_id')->withDefault()->withTrashed();
    }

    /**
     * This has many items.
     */
    public function items()
    {
        return $this->hasMany(EmployeeSalaryTemplateItem::class, 'template_id');
    }

    /**
     * This has many components.
     */
    public function components()
    {
        return $this->belongsToMany(CompanySalarySlipComponent::class, (new EmployeeSalaryTemplateItem())->getTable(), 'template_id', 'component_id')->withPivot('id', 'amount');
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

    /**
     * Scope active.
     */
    public function scopeLastYear($query)
    {
        $now = now();
        $firstDateLastYear = $now->copy()->subYear()->startOfYear();
        $lastDateLastYear = $now->copy()->subYear()->endOfYear();
        return $query->where('prefix', 'Gaji Bulan')->where('start_at', '>=', $firstDateLastYear)->where(fn($subquery) => $subquery->where('end_at', '<=', $lastDateLastYear));
    }

    /**
     * Scope has primary salary.
     */
    public function scopeHasPrimarySalary($query)
    {
        return $query->whereHas('items', fn($item) => $item->where('component_id', 1));
    }

    /**
     * Scope where in year.
     */
    public function scopeWhenInYear($query, $year)
    {
        return $query->when($year, fn($subquery) => $subquery->whereYear('start_at', $year))
            ->when($year, fn($subquery) => $subquery->orWhereYear('end_at', $year));
    }


    /**
     * Scope when year period.
     */
    public function scopeWhenYearPeriod($query, $start_at, $end_at = null)
    {
        return $query->when(
            isset($start_at) && isset($end_at),
            fn($subquery) => $subquery->whereYear('start_at', date('Y', strtotime($start_at)))->whereYear('end_at', date('Y', strtotime($end_at)))
        );
    }
}
