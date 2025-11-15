<?php

namespace Modules\Poz\Http\Requests;

use Auth;
use App\Http\Requests\FormRequest;


class CategoryStoreRequest extends FormRequest
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
            'form.name' => 'required',
            'form.description' => 'required'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'form.name' => 'Nama Brand',
            'form.description' => 'Status'
        ];
    }

    public function messages(): array
    {
        return [
            'form.name.required' => ':attribute harus diisi',
            'form.description.required' => ':attribute harus diisi'
        ];
    }
}
