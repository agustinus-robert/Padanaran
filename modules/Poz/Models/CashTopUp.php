<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashTopUp extends Model
{
    use HasFactory, SoftDeletes;

    public $table = "cash_register_topup";

    protected $fillable = [
        'casier_id',
        'money',
    ];

    protected $casts = [
        'money' => 'float',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}
