<?php

namespace Modules\Reference\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Cacheable\Cacheable;
use App\Models\Traits\Searchable\Searchable;

class Language extends Model
{
    use Cacheable, Searchable;
    
    /**
     * The table associated with the model.
     */
    protected $table = "ref_languages";
    
    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'code', 'name'
    ];

    /**
     * Scope find by code.
     */
    public function scopeFindByCode($query, string $code)
    {
        return $query->whereCode($code)->first();
    }

    /**
     * Scope find by code or fail.
     */
    public function scopeFindByCodeOrFail($query, string $code)
    {
        return $query->whereCode($code)->firstOrFail();
    }
}
