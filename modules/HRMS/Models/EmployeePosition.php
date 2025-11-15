<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Modules\Core\Models\Traits\Approvable\Approver;
use Modules\Core\Models\CompanyPosition;

class EmployeePosition extends Model
{
    use Restorable, Searchable, Approver;

    /**
     * The table associated with the model.
     */
    protected $table = 'empl_positions';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'empl_id',
        'position_id',
        'contract_id',
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
        'name'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * Getter for approver.
     */
    public $approver_label = 'position.name';

    /**
     * Getter for approver.
     */
    public $approver_user = 'employee.user';

    /**
     * This belongs to employee.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'empl_id')->withDefault();
    }

    /**
     * This belongs to contract.
     */
    public function contract()
    {
        return $this->belongsTo(EmployeeContract::class, 'contract_id')->withTrashed()->withDefault();
    }

    /**
     * This belongs to position.
     */
    public function position()
    {
        return $this->belongsTo(CompanyPosition::class, 'position_id')->withTrashed()->withDefault();
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
     * Get is active attribute.
     */
    public function getIsActiveAttribute()
    {
        $now = now();
        return (bool) ($this->start_at <= $now) && ($this->end_at >= $now || is_null($this->end_at));
    }
}
