<?php

namespace Modules\Poz\Http\Requests;

use Auth;
use App\Http\Requests\FormRequest;


class SupplierStoreRequest extends FormRequest
{
    public $placeable;

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
    public function rules(): array
    {
        return [
            'form.code' => 'required',
            'form.name' => 'required',
            'form.email' => 'nullable',
            'form.phone' => 'required',
            'form.address' => 'nullable'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'form.code' => 'Code',
            'form.name' => 'Nama Supplier',
            'form.phone' => 'Nomor HP'
        ];
    }

    public function messages(): array
    {
        return [
            'form.code.required' => ':attribute harus diisi',
            'form.name.required' => ':attribute harus diisi',
            'form.phone.required' => ':attribute harus diisi'
        ];
    }
}
