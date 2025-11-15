<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductQuotationItems extends Model
{
    use HasFactory;

    public $table = "product_quotation_items";

    protected $fillable = [
        'product_quotation_id',
        'name',
        'price',
        'location',
        'image_name',
        'status',
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

    public function quotation()
    {
        return $this->belongsTo(ProductQuotation::class, 'product_quotation_id');
    }
}
