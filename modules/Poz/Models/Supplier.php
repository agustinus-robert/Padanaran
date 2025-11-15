<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Userstamps\Userstamps;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory, SoftDeletes, Userstamps;

    public $table = "supplier";

    protected $fillable = [
        'code',
        'name',
        'phone',
        'email',
        'address',
        'location',
        'image_name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function outlets()
    {
        return $this->belongsToMany(Outlet::class, 'outlet_supplier', 'supplier_id', 'outlet_id');
    }

    public function stock(){
        return $this->hasMany(SupplierSchedule::class, 'supplier_id');
    }
}
