<?php

namespace Modules\Reference\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;

class TaxTier extends Model
{
    use Restorable, Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'ref_tax_rates';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'category',
        'lower_bound',
        'upper_bound',
        'rate'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'category'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        // 
    ];
}
