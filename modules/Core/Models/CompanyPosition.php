<?php

namespace Modules\Core\Models;

use App\Models\Traits\Metable\Metable;
use App\Models\Traits\Cacheable\Cacheable;
use App\Models\Traits\Searchable\Searchable;
use App\Models\Traits\Restorable\Restorable;
use Modules\Core\Enums\PositionLevelEnum;
use Modules\Core\Enums\PositionStudentLevelEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Enums\PositionTypeEnum;

class CompanyPosition extends Model
{
    use Metable, Cacheable, Restorable, Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'cmp_positions';

    /**
     * Define the meta table
     */
    protected $metaTable = 'cmp_position_meta';

    /**
     * Define the meta key name
     */
    public $metaKeyName = 'position_id';

    /**
     * Prevent meta from being populated
     */
    public $hideMeta = true;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'kd',
        'name',
        'description',
        'level',
        'dept_id',
        'is_visible',
        'type'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'level' => PositionLevelEnum::class,
        'type' => PositionTypeEnum::class,
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'name',
        'kd',
        'type',
        'description'
    ];

    /**
     * Get level name attribute.
     */
    protected static function getLevelNameAttribute()
    {
        // return $levels = self::all();
        // return $levels->firstWhere('level', $this->level) ?? 'Tidak setara dengan apapun';
    }

    public function getPositionEnumStudentAttribute(): ?PositionStudentLevelEnum
    {
        try {
            return PositionStudentLevelEnum::from($this->level->value);
        } catch (\ValueError $e) {
            return null; // kalau value invalid/null
        }
    }

    /**
     * This belongs to department.
     */
    public function department()
    {
        return $this->belongsTo(CompanyDepartment::class, 'dept_id')->withDefault();
    }

    /**
     * This belongs to many parents.
     */
    public function parents()
    {
        return $this->belongsToMany(self::class, 'cmp_position_trees', 'position_id', 'parent_id')->orderBy('level');
    }

    /**
     * This belongs to many children.
     */
    public function children()
    {
        return $this->belongsToMany(self::class, 'cmp_position_trees', 'parent_id', 'position_id')->orderBy('level');
    }

    /**
     * Scope visible.
     */
    public function scopeVisibility($query, $bool = true)
    {
        return $query->whereIsVisible($bool ? 1 : 0);
    }

    /**
     * When department id.
     */
    public function scopeWhenDepartmentId($query, $department)
    {
        return $query->when((bool) $department, fn($subquery) => $subquery->whereDeptId($department));
    }

    /**
     * Find by kd.
     */
    public function scopeFindByKd($query, $kd)
    {
        return $query->where('kd', $kd)->firstOrFail();
    }
}
