<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use App\Models\References\Grade;

class CompanyDepartment extends Model
{
    use Restorable, Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'cmp_depts';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'kd', 'name', 'description', 'parent_id', 'is_visible', 'grade_id'
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
     * This belongs to parent.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id')->withDefault();
    }

    /**
     * This has many children.
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * This has many positions.
     */
    public function positions()
    {
        return $this->hasMany(CompanyPosition::class, 'dept_id');
    }

    /**
     * Where visible.
     */
    public function scopeVisible($query)
    {
        return $query->whereIsVisible(1);
    }

    /**
     * When department id.
     */
    public function scopeWhenDepartmentId($query, $department)
    {
        return $query->when($department, fn ($query) => $query->whereDeptId($department));
    }

    /**
     * Find by kd.
     */
    public function scopeFindByKd($query, $kd)
    {
        return $query->where('kd', $kd)->firstOrFail();
    }

    public function grade(){
        return $this->belongsTo(Grade::class, 'grade_id');
    }
}
