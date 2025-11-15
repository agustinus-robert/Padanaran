<?php

namespace Modules\Editor\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Searchable\Searchable;


class EditorWebPages extends Model
{
    use Searchable;

    /**
     * Define the table associated with the model.
     *
     * @var string
     */
    protected $guarded = [];
    protected $table = 'web_pages';
}
