<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Metable\Metable;
use App\Models\Traits\Cacheable\Cacheable;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;

class CompanyUnit extends Model
{
    use Metable, Cacheable, Restorable, Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'cmp_units';

    /**
     * Define the meta table
     */
    protected $metaTable = 'cmp_unit_meta';

    /**
     * Define the meta key name
     */
    public $metaKeyName = 'unit_id';

    /**
     * Prevent meta from being populated
     */
    public $hideMeta = true;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'kd', 'name'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'
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

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * Find by kd.
     */
    public function scopeFindByKd($query, $kd)
    {
        return $query->where('kd', $kd)->firstOrFail();
    }
}
