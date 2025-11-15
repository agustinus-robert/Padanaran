<?php

namespace Modules\Core\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;

class CategoryGroup extends Model
{
    use Restorable;

    /**
     * Define the table associated with the model.
     *
     * @var string
     */
    protected $table = 'inv_category_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['classification_id', 'code', 'title', 'description'];

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
     * Define the relationship with InfrastructureClassification.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Define the relationship with InfrastructureCategory.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function types()
    {
        return $this->hasMany(CategoryGroupType::class, 'group_id');
    }
}
