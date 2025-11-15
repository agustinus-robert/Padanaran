<?php

namespace Modules\Core\Models;

use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Illuminate\Database\Eloquent\Model;

class CompanyBuildingRoom extends Model
{
    use Restorable, Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'cmp_building_rooms';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'building_id', 'kd', 'name', 'meta'
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
     * This belongs to building.
     */
    public function building()
    {
        return $this->belongsTo(CompanyBuilding::class, 'building_id')->withDefault();
    }

    /**
     * This has many inventories.
     */
    public function inventories()
    {
        return $this->morphMany(CompanyInventory::class, 'placeable');
    }
}
