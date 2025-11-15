<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductStock extends Model
{
    use HasFactory, Restorable;

    public $table = "product_stock";

    protected $fillable = [
        'product_id',
        'supplier_id',
        'stockable_type',
        'stockable_id',
        'status',
        'qty',
        'wholesale',
        'pricesale',
        'shift',
        'product_status',
        'is_not_stock',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

     protected static function booted()
    {
        static::addGlobalScope('active_day', function (Builder $builder) {
            if (env('ACTIVE_DAY') === 'TRUE') {
                $builder->whereDate('created_at', now()->toDateString());
            }
        });
    }

    public function outlets()
    {
        return $this->belongsToMany(Outlet::class, 'outlet_product_stock', 'product_stock_id', 'outlet_id');
    }

    public function outlet(){
        return $this->belongsTo(Outlet::class, 'outlet_product_stock', 'product_stock_id', 'outlet_id');
    }

    public function product()
    {
        return $this->belongsTo(\Modules\Poz\Models\Product::class, 'product_id', 'id');
    }

    public function stockable()
    {
        return $this->morphTo();
    }

    public function scopeIsStock($query)
    {
        return $query->whereNull('is_not_stock');
    }
}
