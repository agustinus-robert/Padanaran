<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Modules\Account\Enums\MariageEnum;
use Modules\Account\Enums\SexEnum;

class CompanyPtkp extends Model
{
    use Restorable, Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'cmp_ptkps';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'sex',
        'mariage',
        'child',
        'value',
        'grade_id'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'sex' => SexEnum::class,
        'mariage' => MariageEnum::class,
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
     * The attributes that are searchable.
     */
    public $searchable = [
        //
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        //
    ];
}
