<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Userstamps\Userstamps;
use Modules\Account\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Enums\SupplierPaymentEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductQuotation extends Model
{
    use HasFactory, Restorable, SoftDeletes, Userstamps;

    public $table = "product_quotation";

    protected $fillable = [
        'name',
        'reference',
        'payment_on',
        'status',
        'comments',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'payment_on' => SupplierPaymentEnum::class,
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function productQuotationItems()
    {
        return $this->hasMany(ProductQuotationItems::class, 'product_quotation_id', 'id');
    }

    public function outlets()
    {
        return $this->belongsToMany(Outlet::class, 'outlet_product_quotation', 'quotation_id', 'outlet_id');
    }
}
