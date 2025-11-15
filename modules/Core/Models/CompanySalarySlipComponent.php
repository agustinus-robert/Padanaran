<?php

namespace Modules\Core\Models;

use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Enums\SalaryUnitEnum;
use Modules\Core\Enums\SalaryAllowanceEnum;
use Modules\Core\Enums\SalaryOperateEnum;

class CompanySalarySlipComponent extends Model
{
    use Restorable, Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'cmp_salary_slip_cmpnts';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'kd',
        'slip_id',
        'ctg_id',
        'name',
        'allowance',
        'unit',
        'operate',
        'meta',
        'grade_id'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'unit' => SalaryUnitEnum::class,
        'allowance' => SalaryAllowanceEnum::class,
        'operate' => SalaryOperateEnum::class,
        'meta' => 'object',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
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

    public function slip()
    {
        return $this->belongsTo(CompanySalarySlip::class, 'slip_id')->withDefault();
    }

    public function category()
    {
        return $this->belongsTo(CompanySalarySlipCategory::class, 'ctg_id')->withDefault();
    }
}
