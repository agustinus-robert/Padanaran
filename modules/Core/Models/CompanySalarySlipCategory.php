<?php

namespace Modules\Core\Models;

use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Illuminate\Database\Eloquent\Model;

class CompanySalarySlipCategory extends Model
{
    use Searchable, Restorable;

    /**
     * The table associated with the model.
     */
    protected $table = 'cmp_salary_slip_ctgs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'slip_id', 'name', 'az', 'grade_id'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
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
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'name', 'az'
    ];

    /**
     * This has many slip.
     */
    public function slip()
    {
        return $this->belongsTo(CompanySalarySlip::class, 'slip_id')->withDefault();
    }

    /**
     * This has many components.
     */
    public function components()
    {
        return $this->hasMany(CompanySalarySlipComponent::class, 'ctg_id');
    }
}
