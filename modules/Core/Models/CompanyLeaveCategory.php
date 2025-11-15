<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Searchable\Searchable;
use App\Models\Traits\Restorable\Restorable;

class CompanyLeaveCategory extends Model
{
    use Searchable, Restorable;

    /**
     * The table associated with the model.
     */
    protected $table = 'cmp_leave_ctgs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 'grade_id', 'parent_id', 'meta'
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
     * The attributes that are searchable.
     */
    public $searchable = [
        'name'
    ];

    /**
     * Approvable levels step, see Modules\Core\Enums\PositionLevelEnum.
     */
    public static $approvable_position_levels = [
        4, 3
    ];

    /**
     * This has many children.
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * This has many parent.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id')->withDefault();
    }
}
