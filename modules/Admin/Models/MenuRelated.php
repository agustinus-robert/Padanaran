<?php

namespace Modules\Admin\Models;
use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuRelated extends Model
{
    use HasFactory;

    protected $guarded = [];  
    public $table = "menu_related";
}
