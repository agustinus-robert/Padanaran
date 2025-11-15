<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyInsurancePrice;
use Modules\Docs\Models\Traits\Documentable\Documentable;

class EmployeeInsurance extends Model
{
    use Restorable, Searchable, Documentable;

    /**
     * The table associated with the model.
     */
    protected $table = 'empl_insurances';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'empl_id', 'price_id', 'cmp_price', 'empl_price', 'meta'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'meta' => 'collection',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'deleted_at', 'created_at', 'updated_at'
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
     * This belongs to price.
     */
    public function price()
    {
        return $this->belongsTo(CompanyInsurancePrice::class, 'price_id')->withDefault()->withTrashed();
    }
}
