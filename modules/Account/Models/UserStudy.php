<?php

namespace Modules\Account\Models;

use Illuminate\Database\Eloquent\Model;

class UserStudy extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'user_studies';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'grade_id', 'name', 'diploma_num', 'diploma_at', 'npsn', 'nss', 'from', 'to', 'accreditation', 'district_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'user_id', 'grade_id', 'district_id'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'diploma_at', 'created_at', 'updated_at'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'accreditation_name'
    ];

    /**
     * Enum `accreditation`.
     */
    public static $accreditation = [
        'A',
        'B',
        'C',
        'Belum terakreditasi',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [];

    /**
     * Get accreditation name attributes.
     */
    public function getAccreditationNameAttribute () {
        return self::$accreditation[$this->accreditation] ?? null;
    }

    /**
     * This belongs to user.
     */
    public function user () {
        return $this->belongsTo(User::class);
    }

    /**
     * This belongs to grade.
     */
    public function grade () {
        return $this->belongsTo(\App\Models\References\Grade::class)->withDefault();
    }

    /**
     * This belongs to district.
     */
    public function district () {
        return $this->belongsTo(\App\Models\References\ProvinceRegencyDistrict::class)->withDefault();
    }

    /**
     * Get regional attributes.
     */
    public function getRegionalAttribute () {
        return isset($this->district_id) ? join(', ', [
            $this->district->name,
            $this->district->regency->name,
            $this->district->regency->province->name
        ]) : null;
    }
}
