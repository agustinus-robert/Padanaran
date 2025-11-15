<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transfer extends Model
{
    use HasFactory;

    public $table = "transfer";

    protected $fillable = [
        'reference',
        'transfer_status',
        'transfer_date',
        'transfer_delivered',
        'transfer_completed',
        'transfer_from_warehouse',
        'transfer_to_warehouse',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function transferItems()
    {
        return $this->hasMany(PurchaseItems::class, 'transfer_id');
    }
}
