<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleDirect extends Model
{
    use HasFactory;

    public $table = "sale_direct";

    protected $fillable = [
        'reference',
        'customer_name',
        'desk_name',
        'sale_status',
        'email',
        'subtotal',
        'discount',
        'grand_total',
        'note',
        'bill_at',
        'total_payment',
        'purchase',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'discount' => 'float',
        'total_payment' => 'float',
        'grand_total' => 'float',
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

    public function saleItems()
    {
        return $this->hasMany(SaleDirectItems::class, 'sale_id');
    }
}
