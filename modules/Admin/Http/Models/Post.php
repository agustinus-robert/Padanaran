<?php

namespace Modules\Admin\Models;

use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;

use Kodeine\Metable\Metable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, Metable;

    public $table = "post";
    protected $metaTable = 'post_meta';
}
