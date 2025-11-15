<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Metable\Metable;
use App\Models\Traits\Userstamps\Userstamps;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Modules\Core\Models\CompanyContract;
use Modules\Core\Enums\WorkLocationEnum;
use Modules\Docs\Models\Traits\Documentable\Documentable;

class EmployeeContract extends Model
{
    use Metable, Userstamps, Restorable, Searchable, Documentable;

    /**
     * The table associated with the model.
     */
    protected $table = 'empl_contracts';

    /**
     * Define the meta table
     */
    protected $metaTable = 'empl_contract_meta';

    /**
     * Define the meta key name
     */
    public $metaKeyName = 'contract_id';

    /**
     * Prevent meta from being populated
     */
    public $hideMeta = true;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'empl_id',
        'kd',
        'contract_id',
        'start_at',
        'end_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'work_location' => WorkLocationEnum::class,
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
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'kd',
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
     * This belongs to contract.
     */
    public function contract()
    {
        return $this->belongsTo(CompanyContract::class, 'contract_id')->withDefault();
    }

    /**
     * This has many positions.
     */
    public function positions()
    {
        return $this->hasMany(EmployeePosition::class, 'contract_id');
    }

    /**
     * This has one position.
     */
    public function position()
    {
        return $this->hasOne(EmployeePosition::class, 'contract_id')->active();
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
        return $query->where('start_at', '<=', $now)->where(fn($subquery) => $subquery->where('end_at', '>=', $now)->orWhereNull('end_at'))->orderByDesc('start_at');
    }

    /**
     * Scope active within 7 days.
     */
    public function scopeActiveWithin7Days($query)
    {
        $now = now();
        return $query->where('start_at', '<', today()->addDays(7))->where('start_at', '>=', today());
    }

    /**
     * Scope end next month.
     */
    public function scopeEndNextMonth($query)
    {
        return $query->whereNotNull('end_at')
            ->whereYear('end_at', today()->format('Y'))
            ->whereBetween('end_at', [today()->subMonth(), today()->addMonth()]);
    }

    public function schedules()
    {
        return $this->hasMany(EmployeeContractSchedule::class, 'contract_id');
    }

    /**
     * Scope where active period.
     */
    public function scopeWhereActivePeriod($query, $start_at, $end_at)
    {
        return $query->whereNull('end_at')->orWhere(
            fn($subquery) => $subquery
                ->where(fn($q) => $q->where('start_at', '<=', $start_at)->orWhere('start_at', '<=', $end_at))
                ->where(fn($q) => $q->where('end_at', '>=', $start_at)->orWhere('end_at', '>=', $end_at))
        );
    }
}
