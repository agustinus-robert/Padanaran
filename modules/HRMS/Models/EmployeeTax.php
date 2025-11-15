<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Modules\Core\Models\Traits\Approvable\Approvable;
use Modules\Docs\Models\Traits\Documentable\Documentable;
use Modules\HRMS\Enums\TaxTypeEnum;

class EmployeeTax extends Model
{
    use Restorable, Searchable, Approvable, Documentable;

    /**
     * The table associated with the model.
     */
    protected $table = 'empl_taxs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'empl_id',
        'type',
        'start_at',
        'end_at',
        'meta',
        'file',
        'released_at',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'meta' => 'object',
        'type' => TaxTypeEnum::class,
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'start_at',
        'end_at',
        'deleted_at',
        'created_at',
        'updated_at',
        'released_at'
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
     * Scope when period.
     */
    public function scopeWhenPeriod($query, $start_at, $end_at = null)
    {
        return $query->when(
            isset($start_at) && isset($end_at),
            fn($subquery) => $subquery->whereDate('start_at', '>=', $start_at)->whereDate('end_at', '<=', date('Y-m-t', strtotime($end_at)))
        );
    }
}
