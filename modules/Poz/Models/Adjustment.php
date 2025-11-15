<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Adjustment extends Model
{
    use HasFactory, Restorable;

    public $table = "product_adjustment";

    protected $fillable = [
        'product_id',
        'supplier_id',
        'status',
        'qty',
        'shift',
        'note',
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

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function outlets()
    {
        return $this->belongsToMany(Outlet::class, 'outlet_product_adjustment', 'adjustment_id', 'outlet_id');
    }
}
