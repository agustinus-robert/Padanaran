<?php

namespace Modules\Reference\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Cacheable\Cacheable;
use App\Models\Traits\Searchable\Searchable;

class Timezone extends Model
{
    use Cacheable, Searchable;

    protected $table = "ref_timezones";

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'country', 'coordinat', 'timezone', 'comments', 'offset', 'dstoffset', 'notes'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     */
    protected $hidden = [];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [];
}
