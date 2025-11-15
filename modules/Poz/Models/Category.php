<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Userstamps\Userstamps;
use Modules\Account\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    public $table = "category";

    protected $fillable = [
        'code',
        'name',
        'slug',
        'description',
        'location',
        'image_name',
        'parent_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function outlets()
    {
        return $this->belongsToMany(Outlet::class, 'outlet_category', 'category_id', 'outlet_id');
    }
}
