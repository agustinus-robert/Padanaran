<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Enums\MomentTypeEnum;
use App\Models\Traits\Searchable\Searchable;

class CompanyMoment extends Model
{
    use Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'cmp_moments';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'type', 'date', 'name', 'is_holiday', 'meta'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'type' => MomentTypeEnum::class,
        'meta' => 'object',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'name', 'date'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * When year.
     */
    public function scopeWhenYear($query, $year)
    {
        return $query->when((int) $year, fn ($subquery) => $subquery->whereYear('date', $year));
    }

    /**
     * When month of year.
     */
    public function scopeWhenMonthOfYear($query, $value)
    {
        return $query->when($value, fn ($subquery) => $subquery->whereMonth('date', date('m', strtotime($value)))->whereYear('date', date('Y', strtotime($value))));
    }

    /**
     * Scope holiday.
     */
    public function scopeHoliday($query)
    {
        return $query->where('is_holiday', 1);
    }
}
