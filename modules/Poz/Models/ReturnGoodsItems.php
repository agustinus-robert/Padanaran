<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnGoodsItems extends Model
{
    use HasFactory;

    public $table = "return_items";

    protected $fillable = [
        'return_id',
        'product_id',
        'outlet_id',
        'qty',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function returnGoods()
    {
        return $this->belongsTo(ReturnGoods::class, 'return_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
