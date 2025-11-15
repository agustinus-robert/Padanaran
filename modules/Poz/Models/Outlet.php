<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;
use Modules\Account\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Outlet extends Model
{
    use HasFactory;

    public $table = "outlet";

    protected $fillable = [
        'code',
        'name',
        'admin_id',
        'description',
        'location',
        'image_name'
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_casier_outlet', 'outlet_id', 'user_id');
    }
}
