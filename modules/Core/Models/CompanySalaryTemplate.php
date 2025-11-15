<?php

namespace Modules\Core\Models;

use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Illuminate\Database\Eloquent\Model;

class CompanySalaryTemplate extends Model
{
    use Searchable, Restorable;

    /**
     * The table associated with the model.
     */
    protected $table = 'cmp_salary_templates';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'kd', 'name', 'components', 'meta', 'grade_id'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'components' => 'object',
        'meta' => 'object',
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
        'kd', 'name', 'components'
    ];
}
