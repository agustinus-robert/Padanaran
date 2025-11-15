<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashRegister extends Model
{
    use HasFactory, SoftDeletes;

    public $table = "cash_register";

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

    public function logCash(){
        return $this->hasMany(CashHistoryRegister::class, 'cash_register_id');
    }
}
