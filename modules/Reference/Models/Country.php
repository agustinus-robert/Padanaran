<?php

namespace Modules\Reference\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Cacheable\Cacheable;
use App\Models\Traits\Searchable\Searchable;

class Country extends Model
{
    use Cacheable, Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = "ref_countries";

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'code', 'name', 'native', 'capital'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'states' => 'object',
        'currencies' => 'object',
        'phones' => 'array',
        'languages' => 'object'
    ];

    /**
     * This has many states.
     */
    public function states()
    {
        return $this->hasMany(CountryState::class, 'country_id');
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
