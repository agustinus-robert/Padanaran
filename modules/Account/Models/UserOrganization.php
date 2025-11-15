<?php

namespace Modules\Account\Models;

use Illuminate\Database\Eloquent\Model;

class UserOrganization extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'user_organizations';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'type_id', 'position_id', 'duration', 'year', 'file', 'description'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'user_id', 'type_id', 'position_id'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [];

    /**
     * This belongs to user.
     */
    public function user () {
        return $this->belongsTo(User::class);
    }

    /**
     * This belongs to type.
     */
    public function type () {
        return $this->belongsTo(\App\Models\References\OrganizationType::class)->withDefault();
    }

    /**
     * This belongs to position.
     */
    public function position () {
        return $this->belongsTo(\App\Models\References\OrganizationPosition::class)->withDefault();
    }
}
