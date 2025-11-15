<?php

namespace Modules\Finance\Http\Requests\Report\Tax;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\HRMS\Enums\TaxTypeEnum;
use Modules\HRMS\Models\Employee;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'empl_id'   => 'required|exists:' . (new Employee())->getTable() . ',id',
            'type'      => ['required', new Enum(TaxTypeEnum::class)],
            'start_at'  => 'required',
            'end_at'    => 'nullable',
            'meta'      => 'nullable',
            'file'      => 'nullable|file|mimes:pdf|max:4056'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'empl_id'   => 'karyawan',
            'type'      => 'tipe',
            'start_at'  => 'tanggal mulai',
            'end_at'    => 'tanggal selesai',
            'meta'      => 'meta',
            'file'      => 'lampiran'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            ...$this->only('empl_id', 'type', 'start_at', 'end_at', 'meta'),
            'file' => $this->handleUploadedFile()
        ];
    }

    /**
     * Handle uploaded file
     */
    public function handleUploadedFile()
    {
        return $this->has('files') ? $this->file('files')->store('/employee/taxs') : null;
    }
}
