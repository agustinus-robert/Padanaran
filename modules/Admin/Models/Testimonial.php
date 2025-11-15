<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    public $table = 'testimonial_form';
    
    use HasFactory;
    protected $guarded = []; 
}
