<?php

namespace Modules\Reference\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Cacheable\Cacheable;
use App\Models\Traits\Searchable\Searchable;

class CountryState extends Model
{
    use Cacheable, Searchable;
    
    /**
     * The table associated with the model.
     */
    protected $table = "ref_country_states";
    
    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'name'
    ];

    /**
     * Get full attribute.
     */
    public function getFullAttribute()
    {
        return implode(', ', [$this->name, $this->country->name]);
    }

    /**
     * This belongs to country.
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id')->withDefault();
    }

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
