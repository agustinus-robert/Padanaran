<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    public $table = "sale";

    protected $fillable = [
        'reference',
        'customer_id',
        'student_id',
        'sale_status',
        'discount',
        'pos',
        'sub_total',
        'grand_total',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItems::class, 'sale_id');
    }

    public function outlets()
    {
        return $this->belongsToMany(Outlet::class, 'outlet_sale', 'sale_id', 'outlet_id');
    }
}
