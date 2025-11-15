<?php

namespace Modules\Core\Models;

use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Illuminate\Database\Eloquent\Model;

class CompanyBuilding extends Model
{
    use Restorable, Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'cmp_buildings';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'kd', 'name', 'address_primary', 'address_secondary', 'address_city', 'state_id'
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
        'name'
    ];

    /**
     * This has many rooms.
     */
    public function rooms()
    {
        return $this->hasMany(CompanyBuildingRoom::class, 'building_id');
    }

    /**
     * This has many inventories.
     */
    public function inventories()
    {
        return $this->morphMany(CompanyInventory::class, 'placeable');
    }

    /**
     * This belongsto state.
     */
    public function state()
    {
        return $this->belongsTo(\Modules\Reference\Models\CountryState::class, 'state_id')->withDefault();
    }
}
