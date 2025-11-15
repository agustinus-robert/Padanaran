<?php

namespace Modules\Docs\Models\Traits\Documentable;

use Illuminate\Support\Arr;
use Modules\Docs\Enums\DocumentTypeEnum;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Docs\Models\Document;

trait Documentable
{
    /**
     * Get all of the document.
     */
    public function document()
    {
        return $this->morphOne(Document::class, 'modelable');
    }

    /**
     * Get all of the documents.
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'modelable');
    }

    /**
     * Create new empty document instance.
     */
    public function firstOrCreateDocument($label, $path)
    {
        return Document::firstOrCreate([
            'modelable_type' => get_class($this),
            'modelable_id' => $this->id
        ], [
            'type' => DocumentTypeEnum::SYSTEM,
            'label' => $label ?: Str::random(36),
            'path' => $path,
            'kd' => Str::uuid(),
            'qr' => Str::random(32)
        ]);
    }

    /**
     * Just uploading document.
     */
    public function firstOrUploadDocument($label, $file)
    {
        return $this->firstOrCreateDocument($label, Storage::disk('docs')->store($file));
    }
}
