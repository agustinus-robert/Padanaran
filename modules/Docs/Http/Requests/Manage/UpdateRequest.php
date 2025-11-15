<?php

namespace Modules\Docs\Http\Requests\Manage;

use Storage;
use Str;

class UpdateRequest extends StoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->document);
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        $path = '/company/documents/' . $this->input('type') . '/' . Str::random(36) . '.pdf';

        if ($this->hasFile('files')) {
            $file = Storage::disk('docs')->put($path, file_get_contents($this->file('files')));
        }

        return [
            'type'           => $this->input('type'),
            'label'          => $this->input('label'),
            'path'           => $path,
            'qr'             => Str::random(32),
            'file'           => $file ?? '',
            'meta'  => [
                'description' => $this->input('description'),
                'content'     => $this->input('content'),
            ]
        ];
    }
}
