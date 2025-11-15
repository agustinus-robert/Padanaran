<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignUps extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $table = 'signupuser';
}
