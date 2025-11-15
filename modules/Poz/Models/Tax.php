<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Userstamps\Userstamps;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tax extends Model
{
    use HasFactory, SoftDeletes, Userstamps;

    public $table = "tax_rate";

    protected $fillable = [
        'code',
        'name',
        'actived_on',
        'sale_active',
        'rate',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'rate' => 'float',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function outlets()
    {
        return $this->belongsToMany(Outlet::class, 'outlet_tax_rate', 'tax_id', 'outlet_id');
    }
}
