<?php

namespace Modules\Account\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Searchable\Searchable;
use Kodeine\Metable\Metable;


class MemberVolunteer extends Model
{
    use Searchable, Metable;

    /**
     * Define the table associated with the model.
     *
     * @var string
     */
    protected $guarded = [];
    protected $table = 'member_volunteer';
    protected $metaTable = 'member_volunteer_meta';

    public function user(){
        return $this->belongsTo(User::class);
    }
}