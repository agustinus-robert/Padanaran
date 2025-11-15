<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    public $table = "purchase";

    protected $fillable = [
        'reference',
        'supplier_id',
        'is_pos',
        'purchase_status',
        'warehouse_id',
        'discount',
        'grand_total',
        'purchase_date',
        'purchase_delivered',
        'purchase_completed',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItems::class, 'purchase_id');
    }

    public function outlets()
    {
        return $this->belongsToMany(Outlet::class, 'outlet_purchase', 'purchase_id', 'outlet_id');
    }
}
