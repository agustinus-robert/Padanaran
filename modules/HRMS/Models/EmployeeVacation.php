<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Illuminate\Support\Facades\DB;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\Traits\Approvable\Approvable;
use Modules\Docs\Models\Traits\Documentable\Documentable;

class EmployeeVacation extends Model
{
    use Restorable, Searchable, Approvable, Documentable;

    protected $table = 'empl_vacations';

    protected $fillable = [
        'quota_id',
        'dates',
        'grade_id',
        'description',
        'history',
        'created_at'
    ];

    protected $casts = [
        'dates'      => 'array',
        'history'    => 'array',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'cancelable_dates',
    ];

    public $searchable = [
        'description',
        'quota.category.name',
        'quota.employee.user.name'
    ];

    public static $cancelable_disable_result = [
        ApprovableResultEnum::REVISION
    ];

    public static $cancelable_day_limit = 7;

    public function quota()
    {
        return $this->belongsTo(EmployeeVacationQuota::class, 'quota_id')->withDefault()->withTrashed();
    }

    public function getCancelableDatesAttribute()
    {
        return collect($this->dates)->filter(fn($d) => $d['d'] >= now()->addDays(self::$cancelable_day_limit)->toDateString());
    }

    public function scopeWhereExtractedDatesBetween($query, $start_at, $end_at)
    {
        // PostgreSQL: gunakan ->> untuk mengambil nilai teks
        return $query->whereRaw("
            EXISTS (
                SELECT 1 FROM jsonb_array_elements(dates) AS elem
                WHERE (elem->>'d')::date BETWEEN ? AND ?
            )
        ", [$start_at, $end_at]);
    }

    public function scopeWhenOnlyPending($query, $pending = false)
    {
        return $query->when(
            $pending,
            fn($s) =>
            $s->whereHas(
                'approvables',
                fn($q) =>
                $q->whereResult(ApprovableResultEnum::PENDING)
            )
        );
    }

    public function scopeWhenDatesBetween($query, $start_at = false, $end_at = false)
    {
        return $query->where(function ($q) use ($start_at, $end_at) {
            if ($start_at) {
                $q->whereRaw("EXISTS (
                    SELECT 1 FROM jsonb_array_elements(dates) AS elem
                    WHERE (elem->>'d')::date >= ?
                )", [$start_at]);
            }
            if ($end_at) {
                $q->orWhereRaw("EXISTS (
                    SELECT 1 FROM jsonb_array_elements(dates) AS elem
                    WHERE (elem->>'d')::date <= ?
                )", [$end_at]);
            }
        });
    }

    public function scopeWhenCreatedAtBetween($query, $start_at = false, $end_at = false, $useOr = false)
    {
        return $query->{$useOr ? 'orWhere' : 'where'}(function ($q) use ($start_at, $end_at) {
            if ($start_at) {
                $q->whereDate($this->table . '.created_at', '>=', $start_at);
            }
            if ($end_at) {
                $q->whereDate($this->table . '.created_at', '<=', $end_at);
            }
        });
    }

    public function scopeWhenPeriod($query, $start_at = false, $end_at = false)
    {
        return $query->where(function ($q) use ($start_at, $end_at) {
            $q->whenDatesBetween($start_at, $end_at)
                ->whenCreatedAtBetween($start_at, $end_at, true);
        });
    }

    public function scopeWhenPositionOfDepartment($query, $dep, $pos)
    {
        return $query->when(
            $dep,
            fn($q1) =>
            $q1->whereHas(
                'quota.employee.contract.position.position',
                fn($q2) =>
                $q2->whereIn('dept_id', (array) $dep)
            )->when(
                $pos,
                fn($q3) =>
                $q3->whereHas(
                    'quota.employee.contract.position',
                    fn($q4) =>
                    $q4->whereIn('position_id', (array) $pos)
                )
            )
        );
    }

    public function hasCancelableDates()
    {
        return collect($this->cancelable_dates)->count() > 0;
    }

    public function can($action)
    {
        return match ($action) {
            'revised' => $this->hasAnyApprovableResultIn('REVISION'),
            'deleted' => !$this->hasApprovables() || (
                $this->hasApprovables() &&
                !$this->hasAnyApprovableResultIn('REJECT') &&
                ($this->hasAllApprovableResultIn('PENDING') || $this->hasAnyApprovableResultIn('REVISION'))
            ),
            'canceled' => $this->hasApprovables()
                && $this->approvableTypeIs('approvable')
                && $this->hasAnyApprovableResultIn('APPROVE')
                && $this->hasCancelableDates(),
            default => false,
        };
    }
}
