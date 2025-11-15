<?php

namespace Modules\Admin\Models;
use App\Models\Traits\Restorable\Restorable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryzationMenu extends Model
{
    use HasFactory;

    public $table = "categoryzation_menu";
}
