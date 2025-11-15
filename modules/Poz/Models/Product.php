<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Userstamps\Userstamps;
use Modules\Account\Models\User;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, Restorable, SoftDeletes, Userstamps;

    public $table = "product";

    protected $fillable = [
        'type',
        'alert_qty',
        'code',
        'name',
        'barcode',
        'brand_id',
        'category_id',
        'sub_category_id',
        'unit_id',
        'tax_rate_id',
        'price',
        'location',
        'image_name',
        'wholesale',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function outlets()
    {
        return $this->belongsToMany(Outlet::class, 'outlet_product', 'product_id', 'outlet_id');
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItems::class, 'product_id', 'id');
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItems::class, 'product_id', 'id');
    }

    public function saleDirectItems(){
        return $this->hasMany(SaleDirectItems::class, 'product_id', 'id');
    }

    public function productStockAdjustItems()
    {
        return $this->hasMany(ProductStock::class, 'product_id', 'id');
    }

    public function schedule(){
        return $this->hasMany(SupplierSchedule::class, 'product_id');
    }
}
