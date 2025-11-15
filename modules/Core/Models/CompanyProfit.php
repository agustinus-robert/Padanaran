<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Restorable\Restorable;

class CompanyProfit extends Model
{
    use Restorable;

    /**
     * The table associated with the model.
     */
    protected $table = 'cmp_profits';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'start_at', 'end_at', 'amount'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'start_at' => 'datetime', 'end_at' => 'datetime'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        // 
    ];

    /**
     * Scope where strict period in.
     */
    public function scopeWhereStrictPeriodIn($query, $start_at, $end_at)
    {
        return $query->where('start_at', date('Y-m-d', strtotime($start_at)))->where('end_at', date('Y-m-d', strtotime($end_at)));
    }
}
