<?php

namespace Modules\Poz\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Casier extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $table = "user_casier_outlet";

    protected $fillable = [
        'user_id',
        'outlet_id',
    ];
}
