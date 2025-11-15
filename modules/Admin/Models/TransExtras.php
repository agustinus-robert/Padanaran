<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransExtras extends Model
{
    use HasFactory;

    protected $guarded = [];  
    
    public $table = "transaction_extras";
}
