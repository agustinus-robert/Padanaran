<?php

namespace Modules\Account\Models;

use Illuminate\Database\Eloquent\Model;

class UserMother extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = "user_mother";

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'user_id';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id', 'mother_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     */
    protected $hidden = [];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [];

    /**
     * The attributes that define validation rules.
     */
    public static $rules = [
        'user_id'       => 'required|exists:users,id',
        'mother_id'       => 'required|exists:users,id',
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
     * This belongs to mother.
     */
    public function mother () {
        return $this->belongsTo(User::class, 'mother_id');
    }
}
