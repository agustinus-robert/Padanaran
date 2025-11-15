<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regencies extends Model
{
    protected $table = "ref_province_regencies";

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [];
}
