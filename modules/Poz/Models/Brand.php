<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Userstamps\Userstamps;
use Modules\Account\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory, SoftDeletes, Userstamps;

    public $table = "brand";

    protected $fillable = [
        'code',
        'name',
        'slug',
        'description',
        'location',
        'image_name',
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
        return $this->hasMany(Product::class, 'brand_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function outlets()
    {
        return $this->belongsToMany(Outlet::class, 'outlet_brand', 'brand_id', 'outlet_id');
    }
}
