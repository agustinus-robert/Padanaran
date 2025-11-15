<?php

namespace Modules\Admin\Models;
use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;

use Kodeine\Metable\Metable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostVideo extends Model
{
    use HasFactory;

    public $table = "post_video";
}
