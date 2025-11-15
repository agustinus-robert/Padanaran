<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityTransaction extends Model
{
    public $table = "transaction_facility_has_person";
    
    use HasFactory;
}
