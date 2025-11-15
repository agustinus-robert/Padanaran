<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Carbon\Carbon;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Enums\LoanTenorEnum;
use Modules\Core\Models\CompanyLoanCategory;
use Modules\Core\Models\Traits\Approvable\Approvable;
use Modules\Docs\Models\Traits\Documentable\Documentable;

class EmployeeLoan extends Model
{
    use Restorable, Searchable, Approvable, Documentable;

    /**
     * The table associated with the model.
     */
    protected $table = 'empl_loans';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'empl_id',
        'ctg_id',
        'description',
        'amount_total',
        'tenor',
        'tenor_by',
        'submission_at',
        'paid_at',
        'start_at',
        'meta',
        'parent_id',
        'approved_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'tenor_by' => LoanTenorEnum::class,
        'meta' => 'object'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'submission_at',
        'paid_at',
        'start_at',
        'deleted_at',
        'created_at',
        'updated_at',
        'approved_at'
    ];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'description',
        'employee.user.name',
        'category.name'
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
    public function category()
    {
        return $this->belongsTo(CompanyLoanCategory::class, 'ctg_id')->withDefault()->withTrashed();
    }

    /**
     * This has many installments.
     */
    public function installments()
    {
        return $this->hasMany(EmployeeLoanInstallment::class, 'loan_id');
    }

    /**
     * This belongs to parent.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * This has many childrens.
     */
    public function childrens()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * This has many transactions through installments.
     */
    public function transactions()
    {
        return $this->hasManyThrough(EmployeeLoanInstallmentTransaction::class, EmployeeLoanInstallment::class, 'loan_id', 'installment_id');
    }

    /* *
     * search by date (not used)
     */
    public function scopeSearchByDate($query, $start_at, $end_at)
    {
        return $query->when($start_at, function ($date) use ($start_at, $end_at) {
            return $date->whereBetween('submission_at', [Carbon::parse($start_at), Carbon::parse($end_at)]);
        });
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
     * Scope when only pending.
     */
    public function scopeWhenOnlyPending($query, $pending = false)
    {
        return $query->when($pending, fn($s) => $s->whereHas('approvables', fn($approvable) => $approvable->whereResult(ApprovableResultEnum::PENDING)));
    }

    /**
     * Scope when only pending.
     */
    public function scopePaidOff($query, $paidoff = false)
    {
        switch ($paidoff) {
            case 1:
                return $query->whereNotNull('paid_at');
                break;

            default:
                return $query->whereNull('paid_at');
                break;
        }
    }

    /* *
     * is Approved
     */
    public function scopeIsApproved($query)
    {
        return $query->whereNotNull('approved_at');
    }

    /* *
     * is on payment
     */
    public function scopeInPayment($query)
    {
        return $query->whereNull('paid_at');
    }

    /**
     * Authorization scopes.
     */
    public function can($action)
    {
        return match ($action) {
            'revised' => $this->hasAnyApprovableResultIn('REVISION'),
            'deleted' => !$this->hasApprovables() || ($this->hasApprovables() && !$this->hasAnyApprovableResultIn('REJECT') && ($this->hasAllApprovableResultIn('PENDING') || $this->hasAnyApprovableResultIn('REVISION'))),
            'canceled' => $this->hasApprovables() && $this->approvableTypeIs('approvable') && $this->hasAnyApprovableResultIn('APPROVE'),
            default => false,
        };
    }
}
