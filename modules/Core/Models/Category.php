<?php

namespace Modules\Core\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Restorable;

    /**
     * Define the table associated with the model.
     *
     * @var string
     */
    protected $table = 'inv_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'title', 'description'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
     * Define the relationship with InfrastructureCategory.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany(CategoryGroup::class, 'category_id');
    }

    /**
     * Define the relationship with InfrastructureCategory.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyTrough
     */
    public function types()
    {
        return $this->hasManyThrough(CategoryGroupType::class, CategoryGroup::class, 'group_id', 'category_id');
    }
}
