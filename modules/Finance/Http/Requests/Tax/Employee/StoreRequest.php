<?php

namespace Modules\Finance\Http\Requests\Tax\Employee;

use App\Http\Requests\FormRequest;
use Modules\Account\Models\User;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'user_id'     => 'required|exists:' . (new User())->getTable() . ',id',
            'tax_number'  => 'required|string|max:191',
            'tax_address' => 'nullable|string|max:191',
            'files'       => 'nullable|file|mimes:pdf|max:2048',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'user_id'     => 'karyawan',
            'tax_number'  => 'no NPWP',
            'tax_address' => 'alamat sesuai NPWP',
            'files'       => 'lampiran file NPWP',
        ];
    }

    /**
     * Transform request data into expected output.
     */
    public function transform()
    {
        return $this->only(['user_id', 'tax_number', 'tax_address', 'tax_file' => $this->handleUploadedFile()]);
    }

    /**
     * Handle uploaded file
     */
    public function handleUploadedFile()
    {
        return $this->has('files') ? $this->file('files')->store('users/' . $this->employee->user_id . '/tax/files') : null;
    }
}
