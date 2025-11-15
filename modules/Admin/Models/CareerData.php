<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerData extends Model
{
    protected $guarded = [];
    public $table = 'career_data';
    use HasFactory;
}
