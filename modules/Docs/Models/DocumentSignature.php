<?php

namespace Modules\Docs\Models;

use Arr;
use Str;
use Storage;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Traits\Restorable\Restorable;
use Modules\Account\Models\User;

class DocumentSignature extends Model
{
    use Restorable;

    /**
     * The table associated with the model.
     */
    protected $table = 'doc_signatures';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'doc_id', 'qr', 'user_id'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * This belongs to document.
     */
    public function document()
    {
        return $this->belongsTo(Document::class, 'doc_id')->withDefault();
    }

    /**
     * This belongs to user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    /**
     * Scope where user id in array.
     */
    public function scopeWhereUserIdIn($query, $array = [])
    {
        return $query->whereIn('user_id', (array) $array);
    }
}
