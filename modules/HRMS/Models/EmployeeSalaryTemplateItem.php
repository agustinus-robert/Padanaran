<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Modules\Core\Models\CompanySalarySlipComponent;

class EmployeeSalaryTemplateItem extends Model
{
    use Restorable, Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'empl_salary_template_items';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'template_id', 'component_id', 'slip_az', 'slip_name', 'ctg_az', 'ctg_name', 'az', 'name', 'description', 'amount'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'start_at', 'end_at', 'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'name', 'template.employee.user.name'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * This belongs to template.
     */
    public function template()
    {
        return $this->belongsTo(EmployeeSalaryTemplate::class, 'template_id')->withDefault()->withTrashed();
    }

    /**
     * This belongs to component.
     */
    public function component()
    {
        return $this->belongsTo(CompanySalarySlipComponent::class, 'component_id')->withDefault()->withTrashed();
    }
}
