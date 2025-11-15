<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleDirectItems extends Model
{
    use HasFactory;

    public $table = "sale_direct_items";

    protected $fillable = [
        'sale_id',
        'product_id',
        'product_name',
        'qty',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'price' => 'float',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleDirectItems::class, 'sale_id');
    }
}
