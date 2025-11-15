<?php

namespace Modules\Admin\Http\Requests\Employment\Employee;

class UpdateRequest extends StoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->employee);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'joined_at'         => 'required|date',
            'permanent_at'      => 'nullable|date',
            'kd'                => 'nullable|string',
            'permanent_kd'      => 'nullable|string',
            'files'             => 'nullable|file|mimes:pdf|max:4096',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'joined_at'         => 'tanggal bergabung',
            'permanent_at'      => 'tanggal penetapan karyawan',
            'kd'                => 'nomor induk karyawan tetap',
            'permanent_kd'      => 'nomor surat penetapan karyawan tetap',
            'files'             => 'lampiran penetapan karyawan tetap',
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            ...$this->only('joined_at', 'permanent_at', 'kd', 'permanent_kd'),
            'permanent_sk' => $this->handleUploadedFile()
        ];
    }

    /**
     * Handle uploaded file
     */
    public function handleUploadedFile()
    {
        return $this->has('files') ? $this->file('files')->store('users/' . $this->employee->user_id . '/employees/spkt') : null;
    }
}
