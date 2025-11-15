<?php

namespace Modules\Core\Models;

use Modules\Core\Enums\OutworkScheduleEnum;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Searchable\Searchable;
use App\Models\Traits\Restorable\Restorable;

class CompanyOutworkCategory extends Model
{
    use Searchable, Restorable;

    /**
     * The table associated with the model.
     */
    protected $table = 'cmp_outwork_ctgs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 'description', 'price', 'meta', 'grade_id'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
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
     * The attributes that are searchable.
     */
    public $searchable = [
        'name'
    ];
}
