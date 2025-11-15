<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanySalarySlipComponent;
use Modules\Core\Models\Traits\Approvable\Approvable;
use Modules\HRMS\Enums\DeductionTypeEnum;

class EmployeeDeduction extends Model
{
    use Restorable, Searchable, Approvable;

    /**
     * The table associated with the model.
     */
    protected $table = 'empl_deductions';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'empl_id',
        'type',
        'component_id',
        'amount',
        'description',
        'paid_at',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'type' => DeductionTypeEnum::class,
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'paid_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'description',
        'employee.user.name',
        'component.name'
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
     * This belongs to category.
     */
    public function component()
    {
        return $this->belongsTo(CompanySalarySlipComponent::class, 'component_id')->withDefault()->withTrashed();
    }

    /**
     * Scope when period.
     */
    public function scopeWhenPeriod($query, $start_at = false, $end_at = false)
    {
        return $query->where(
            fn($subquery) => $subquery->when($start_at, fn($q) => $q->whereDate($this->table . '.created_at', '>=', $start_at))
                ->when($end_at, fn($q) => $q->whereDate($this->table . '.created_at', '<=', $end_at))
        );
    }

    /**
     * Scope when only pending.
     */
    public function scopeWhenOnlyPending($query, $pending = false)
    {
        return $query->when($pending, fn($s) => $s->whereHas('approvables', fn($approvable) => $approvable->whereResult(ApprovableResultEnum::PENDING)));
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

    /**
     * Authorization scopes.
     */
    public function can($action)
    {
        return match ($action) {
            'deleted' => !$this->hasApprovables() || ($this->hasApprovables() && !$this->hasAnyApprovableResultIn('REJECT') && ($this->hasAllApprovableResultIn('PENDING') || $this->hasAnyApprovableResultIn('REVISION'))),
            default => false,
        };
    }
}
