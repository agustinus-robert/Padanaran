<?php

namespace Modules\Poz\Http\Requests;

use Auth;
use App\Http\Requests\FormRequest;


class TaxStoreRequest extends FormRequest
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
            'form.rate' => 'required',
            'form.actived_on' => 'required'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'form.name' => 'Nama Pajak',
            'form.rate' => 'Tarif Pajak',
            'form.actived_on' => 'Tipe pajak harus dinisiasikan'
        ];
    }

    public function messages(): array
    {
        return [
            'form.name.required' => ':attribute harus diisi',
            'form.rate.required' => ':attribute harus diisi',
            'form.actived_on.required' => ':attribute harus diisi'
        ];
    }
}
