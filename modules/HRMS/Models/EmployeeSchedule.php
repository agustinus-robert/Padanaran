<?php

namespace Modules\HRMS\Models;

use App\Models\Traits\Searchable\Searchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Modules\HRMS\Enums\WorkShiftEnum;
use Modules\Academic\Models\AcademicSubject;

class EmployeeSchedule extends Model
{
    use Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'empl_schedules';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'empl_id',
        'start_at',
        'end_at',
        'dates',
        'data',
        'workdays_count'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'dates'      => 'array',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'start_at'   => 'datetime',
        'end_at'     => 'datetime',
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

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'period'
    ];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'employee.user.name'
    ];

    /**
     * Get period attribute.
     */
    public function getPeriodAttribute()
    {
        return $this->start_at ? date('Y-m', strtotime($this->start_at)) : null;
    }

    /**
     * This belongs to employee.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'empl_id')->withDefault()->withTrashed();
    }

    public function lesson($idLesson){
        return AcademicSubject::find($idLesson);
    }

    /**
     * Scope when month of year.
     */
    public function scopeWhenMonth($query, $month)
    {
        return $query->when(
            isset($month),
            fn($subquery) => $subquery->whereDate('start_at', '>=', date('Y-m-01', strtotime($month)))->whereDate('end_at', '<=', date('Y-m-t', strtotime($month)))
        );
    }

    /**
     * Where month of year.
     */
    public function scopeWhereMonthOfYear($query, $value, $or = false)
    {
        return $query->{$or ? 'orWhere' : 'where'}(
            fn($q) => $q->where(fn($subquery) => $subquery->whereMonth('start_at', date('m', strtotime($value)))->whereYear('start_at', date('Y', strtotime($value))))
                ->orWhere(fn($subquery) => $subquery->whereMonth('end_at', date('m', strtotime($value)))->whereYear('end_at', date('Y', strtotime($value))))
        );
    }

    /**
     * Or where month of year.
     */
    public function scopeOrWhereMonthOfYear($query, $value)
    {
        return $this->scopeWhereMonthOfYear($query, $value, true);
    }

    /**
     * When month of year.
     */
    public function scopeWhenMonthOfYear($query, $value)
    {
        return $query->when($value, fn($subquery) => $subquery->whereMonthOfYear($value));
    }

    /**
     * When position of department.
     */
    public function scopeWhenPositionOfDepartment($query, $dep, $pos)
    {
        return $query->when(
            $dep,
            fn($q1) =>
            $q1->whereHas('contract.position.position', fn($q3) => $q3->whereIn('dept_id', (array) $dep))->when(
                $pos,
                fn($q2) =>
                $q2->whereHas('contract.position', fn($q3) => $q3->whereIn('position_id', (array) $pos))
            )
        );
    }

    /**
     * Scope when period.
     */
    public function scopeWhenPeriod($query, $start_at, $end_at = null)
    {
        return $query->when(
            isset($start_at) && isset($end_at),
            fn($subquery) => $subquery->whereDate('start_at', '>=', $start_at)->whereDate('end_at', '<=', date('Y-m-t', strtotime($end_at)))
        );
    }

    /**
     * Where period in.
     */
    public function scopeWherePeriodIn($query, $start_at, $end_at)
    {
        return $query->where(fn($q1) => $q1->where('start_at', '>=', $start_at)->where('start_at', '<=', $end_at))
            ->orWhere(fn($q2) => $q2->where('end_at', '>=', $start_at)->where('end_at', '<=', $end_at));
    }

    /**
     * Get entry logs.
     */
    public function scopePluckDatesWherePeriodIn($query, Carbon $start_at, Carbon $end_at)
    {
        return $query->wherePeriodIn($start_at, $end_at)
            ->pluck('dates')
            ->mapWithKeys(fn($v) => $v)
            ->filter(fn($v, $k) => $start_at->lte(Carbon::parse($k)) && $end_at->gte(Carbon::parse($k)));
    }

    /**
     * Get entry logs.
     */
    public function getEntryLogs(Collection $scanlogs, $withNull = false)
    {
        $entries = [];
        foreach ($this->getAttribute('dates') as $date => $shifts) {
            foreach ($shifts as $i => $shift) {
                $workshift = WorkShiftEnum::tryFrom($i + 1);
                if ($withNull ? $shift : array_filter($shift)) {
                    $schedule = Carbon::parse($shift[0]);
                    $in = isset($scanlogs[$date]) ? $scanlogs[$date]
                        ->filter(fn($log) => count(array_filter($workshift->tolerance()['in'])) ? $log->created_at->between($schedule->copy()->sub($workshift->tolerance()['in'][0]), $schedule->copy()->add($workshift->tolerance()['in'][1])) : true)
                        ->values()
                        ->map(fn($log) => $log->created_at)
                        ->first() : null;
                    $out = isset($scanlogs[$date]) && count($scanlogs[$date]) > 1 ? $scanlogs[$date]->last()->created_at : null;
                    $entries[$date][] = (object) [
                        'bool' => !is_null($in),
                        'date' => $date,
                        'shift' => $workshift,
                        'schedule' => array_map(fn($v) => isset($v) ? Carbon::parse($v) : null, $shift),
                        'in' => $in,
                        'out' => $out,
                        'location' => isset($scanlogs[$date]) ? $scanlogs[$date]->pluck('location')->unique()->toArray() : [],
                        'ontime' => $in ? $schedule->copy()->add('59 seconds')->gte($in) : null
                    ];
                }
            }
        }

        return $entries;
    }
}
