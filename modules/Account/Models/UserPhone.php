<?php

namespace Modules\Account\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPhone extends Model
{
    use SoftDeletes;

    private $code = '62';

    /**
     * The table associated with the model.
     */
    protected $table = 'user_phones';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'number', 'verified_at', 'whatsapp'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'user_id'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'verified_at', 'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'whatsapp'   => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'formatted',
        'whatsapp_link',
    ];

    /**
     * Get formatted attributes.
     */
    public function getFormattedAttribute () {
        return $this->number ? $this->code.((real) substr($this->number, 0, 2) == $this->code ? substr($this->number, 2) : (real) $this->number) : null;
    }

    /**
     * Get whatsapp link attributes.
     */
    public function getWhatsappLinkAttribute () {
        return $this->whatsapp == true ? 'https://wa.me/'.$this->formatted : null;
    }

    /**
     * This belongs to user.
     */
    public function user () {
        return $this->belongsTo(User::class);
    }

    /**
     * This verify email.
     */
    public function verify () {
        $this->update([
            'verified_at' => date('Y-m-d H:i:s')
        ]);

        return $this;
    }

    /**
     * This unverify email.
     */
    public function unverify () {
        $this->update([
            'verified_at' => null
        ]);

        return $this;
    }
}
