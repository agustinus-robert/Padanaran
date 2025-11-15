<?php

namespace Modules\Docs\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Modules\Account\Models\User;
use Modules\Core\Models\Traits\Queueable;
use Modules\Docs\Enums\DocumentTypeEnum;

class Document extends Model
{
    use Restorable, Searchable, Queueable;

    /**
     * The table associated with the model.
     */
    protected $table = 'docs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'kd', 'type', 'qr', 'label', 'path', 'modelable_type', 'modelable_id', 'meta'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'type' => DocumentTypeEnum::class,
        'meta' => 'object',
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
    protected $appends = [
        'filename'
    ];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'label'
    ];

    /**
     * Get filename attribute.
     */
    public function getFilenameAttribute()
    {
        return isset($this->path) ? Arr::last(explode('/', $this->path)) : null;
    }

    /**
     * Get the parent modelable model (employee contracts, vacations, etc.).
     */
    public function modelable()
    {
        return $this->morphTo();
    }

    /**
     * This has many signatures.
     */
    public function signatures()
    {
        return $this->hasMany(DocumentSignature::class, 'doc_id');
    }

    /**
     * This belongs to many users.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'doc_signatures', 'doc_id', 'user_id')->withPivot('qr')->withTimestamps();;
    }

    /**
     * Redirect to document via storage.
     */
    public function show()
    {
        return $this->path ? redirect(Storage::disk('docs')->url($this->path)) : abort(404);
    }

    /**
     * Show url string based on path.
     */
    public function url()
    {
        return Storage::disk('docs')->exists($this->path) ? Storage::disk('docs')->url($this->path) : null;
    }

    /**
     * Sign user to document.
     */
    public function sign($ids = [], $withoutDetach = false)
    {
        $users = [];
        foreach ($ids as $id) {
            $users[$id] = ['qr' => Str::random(32)];
        }

        return count($users) ? $this->users()->sync($users) : true;
    }
}
