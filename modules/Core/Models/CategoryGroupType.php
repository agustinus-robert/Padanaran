<?php

namespace Modules\Core\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;

class CategoryGroupType extends Model
{
    use Restorable;

    /**
     * Define the table associated with the model.
     *
     * @var string
     */
    protected $table = 'inv_category_group_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['group_id', 'code', 'title', 'description'];

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
     * Define the relationship with ClassificationGroup.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(CategoryGroup::class, 'group_id');
    }
}
