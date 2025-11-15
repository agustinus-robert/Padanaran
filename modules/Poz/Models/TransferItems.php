<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransferItems extends Model
{
    use HasFactory;

    public $table = "transfer_items";

    protected $fillable = [
        'reference',
        'transfer_id',
        'product_id',
        'from_warehouse_id',
        'to_warehouse_id',
        'qty',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
