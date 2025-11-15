<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserOutlet extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = "user_casier_outlet";

    protected $fillable = [
        'user_id',
        'outlet_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }
}
