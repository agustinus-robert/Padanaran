<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransRoomRooms extends Model
{
    use HasFactory;

    protected $guarded = [];  
    
    public $table = "transaction_room_has_rooms";
}
