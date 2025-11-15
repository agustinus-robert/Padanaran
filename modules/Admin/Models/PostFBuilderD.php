<?php

namespace Modules\Admin\Models;
use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostFBuilderD extends Model
{
    use HasFactory;

    public $table = "post_form_builder_data";
}
