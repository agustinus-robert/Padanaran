<?php

namespace Modules\Core\Models;

use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Illuminate\Database\Eloquent\Model;

class CompanyInsurancePrice extends Model
{
    use Restorable, Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'cmp_insurance_prices';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'insurance_id', 'conditions', 'cmp_price', 'cmp_price_type', 'empl_price', 'empl_price_type', 'price_factor', 'price_factor_additional'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'conditions' => 'collection',
        'price_factor_additional' => 'array',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
        // 'cmp_price_type' => InsurancePriceTypeEnum::class,
        // 'empl_price_type' => InsurancePriceTypeEnum::class
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
    protected $appends = [];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'name'
    ];

    /**
     * This belongsTo insurance.
     */
    public function insurance()
    {
        return $this->belongsTo(CompanyInsurance::class, 'insurance_id');
    }
}
