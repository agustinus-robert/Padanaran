<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Cacheable\Cacheable;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Modules\Core\Enums\PayrollSettingEnum;

class CompanyPayrollSetting extends Model
{
    use Cacheable, Searchable, Restorable;

    /**
     * The table associated with the model.
     */
    protected $table = "cmp_payroll_settings";

    /* *
     * fillable column
     */
    protected $fillable = [
        'key', 'az', 'meta', 'grade_id'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'meta' => 'object',
        'az' => PayrollSettingEnum::class,
        'deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'
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
        'key'
    ];
}
