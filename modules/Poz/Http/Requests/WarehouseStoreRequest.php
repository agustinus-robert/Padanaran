<?php

namespace Modules\Poz\Http\Requests;

use Auth;
use App\Http\Requests\FormRequest;


class WarehouseStoreRequest extends FormRequest
{
    public $placeable;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', Inquiry::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'form.name' => 'required',
            'form.location' => 'required',
            'form.phone' => 'nullable',
            'form.email' => 'nullable',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'form.name' => 'Nama Gudang',
            'form.location' => 'Lokasi Gudang',
        ];
    }

    public function messages(): array
    {
        return [
            'form.name.required' => ':attribute harus diisi',
            'form.location.required' => ':attribute harus diisi',
        ];
    }
}
