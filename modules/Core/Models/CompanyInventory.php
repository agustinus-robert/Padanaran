<?php

namespace Modules\Core\Models;

use App\Models\Traits\Metable\Metable;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Illuminate\Database\Eloquent\Model;
use Modules\Account\Models\User;

class CompanyInventory extends Model
{
    use Restorable, Searchable, Metable;

    /**
     * Define the table associated with the model.
     *
     * @var string
     */
    protected $table = 'cmp_inventories';

    /**
     * Define the meta table
     */
    protected $metaTable = 'cmp_inventories_meta';

    /**
     * Define the meta key name
     */
    public $metaKeyName = 'inventory_id';

    /**
     * Prevent meta from being populated
     */
    public $hideMeta = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kd', 'name', 'placeable_type', 'placeable_id', 'ctg_id', 'condition', 'quantity', 'bought_price', 'bought_at', 'sold_at'

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'placeable' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * The attributes that should be searchable.
     *
     * @var array
     */
    protected $searchable = [];

    /**
     * Define the relationship with User for PIC (Person In Charge).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    /**
     * Define the relationship with category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'ctg_id');
    }
}
