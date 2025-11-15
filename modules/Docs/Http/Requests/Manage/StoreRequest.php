<?php

namespace Modules\Docs\Http\Requests\Manage;

use Str;
use Storage;
use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Docs\Enums\DocumentTypeEnum;
use Modules\Docs\Models\Document;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', Document::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'label'       => 'required|string',
            'type'        => ['required', new Enum(DocumentTypeEnum::class)],
            'description' => 'nullable|string',
            'content'     => 'nullable|string',
            'file'        => 'nullable|file|mimes:pdf|max:4096'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'label'       => 'nama dokumen',
            'type'        => 'tipe dokumen',
            'description' => 'deskripsi dokumen',
            'content'     => 'isi dokumen',
            'file'        => 'lampiran',
        ];
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
            'file'           => $file ?? '',
            'kd'             => Str::uuid(),
            'qr'             => Str::random(32),
            'modelable_type' => Document::class,
            'modelable_id'   => Document::max('id') + 1,
            'meta'  => [
                'description' => $this->input('description'),
                'content'     => $this->input('content'),
            ]
        ];
    }
}
