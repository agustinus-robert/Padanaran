<?php

namespace Modules\Account\Models;

use Illuminate\Database\Eloquent\Model;

class UserAppreciation extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'user_appreciations';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 'territory_id', 'year', 'file', 'description'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'user_id', 'territory_id'
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
     * This belongs to territory.
     */
    public function territory () {
        return $this->belongsTo(\App\Models\References\Territory::class)->withDefault();
    }
}
