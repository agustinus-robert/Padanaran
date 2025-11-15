<?php

namespace Modules\Admin\Models;

use App\Models\Traits\Restorable\Restorable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $table = "categoryzation";

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_has_category', 'tags_id', 'post_id');
    }
}
