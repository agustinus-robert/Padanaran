<?php

namespace Modules\Reference\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Cacheable\Cacheable;
use App\Models\Traits\Searchable\Searchable;

class Grade extends Model
{
    use Cacheable, Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = "ref_grades";
    
    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;
}
