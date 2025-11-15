<?php

namespace Modules\Account\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserProfile extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'user_profile';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
    	'name', 'prefix', 'suffix', 'pob', 'dob', 'sex', 'blood', 'religion_id', 'is_dead', 'avatar', 'country_id', 'nik', 'child_num', 'siblings_count', 'diseases', 'height', 'weight', 'hobby_id', 'desire_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'country_id', 'religion_id', 'user_id', 'hobby_id', 'desire_id'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'dob'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'is_dead'   => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'display_name',
        'full_name',
        'sex_name',
        'blood_name',
        'is_dead_name',
        'dob_name',
        'avatar_path'
    ];

    /**
     * Enum `sex`.
     */
    public static $sex = [
        'Laki-laki',
        'Perempuan'
    ];

    /**
     * Enum `blood`.
     */
    public static $blood = [
        'A',
        'B',
        'AB',
        'O',
    ];

    /**
     * Enum `is_dead`.
     */
    public static $is_dead = [
        'Masih hidup',
        'Sudah meninggal'
    ];

    /**
     * Get fullname attributes.
     */
    public function getFullNameAttribute () {
        return (join(', ', array_filter([join(' ', array_filter([$this->prefix, $this->name])), $this->suffix])) ?: null).($this->is_dead ? ' (ALM)' : '');
    }

    /**
     * Get display name attributes.
     */
    public function getDisplayNameAttribute () {
        return $this->user_id == auth()->id() ? 'Anda' : $this->name;
    }

    /**
     * Get dob name attributes.
     */

    public function getDobNameAttribute()
    {
        return $this->dob ? Carbon::parse($this->dob)->formatLocalized('%d %B %Y') : null;
    }

    /**
     * Get sex attributes.
     */
    public function getSexNameAttribute () {
        return self::$sex[$this->sex] ?? null;
    }

    /**
     * Get blood attributes.
     */
    public function getBloodNameAttribute () {
        return self::$blood[$this->blood] ?? null;
    }

    /**
     * Get is dead attributes.
     */
    public function getIsDeadNameAttribute () {
        return self::$is_dead[$this->is_dead] ?? null;
    }

    /**
     * Get avatar path attributes.
     */
    public function getAvatarPathAttribute () {
        return $this->avatar ? \Storage::url($this->avatar) : asset('img/default-avatar.svg');
    }

    /**
     * This belongs to user.
     */
    public function user () {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * This belongs to country.
     */
    public function country () {
        return $this->belongsTo(\App\Models\References\Country::class, 'country_id');
    }

    /**
     * This belongs to religion.
     */
    public function religion () {
        return $this->belongsTo(\App\Models\References\Religion::class, 'religion_id');
    }

    /**
     * This belongs to hobby.
     */
    public function hobby () {
        return $this->belongsTo(\App\Models\References\Hobby::class, 'hobby_id');
    }

    /**
     * This belongs to desire.
     */
    public function desire () {
        return $this->belongsTo(\App\Models\References\Desire::class, 'desire_id');
    }
}
