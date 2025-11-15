<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnGoods extends Model
{
    use HasFactory;

    public $table = "return";

    protected $fillable = [
        'reference',
        'return_status',
        'sub_total',
        'grand_total',
        'return_note',
        'casier_note',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function returItems()
    {
        return $this->hasMany(ReturnGoodsItems::class, 'sale_id');
    }

    public function outlets()
    {
        return $this->belongsToMany(Outlet::class, 'outlet_return', 'return_id', 'outlet_id');
    }
}
