<?php

namespace Modules\Finance\Http\Requests\Service\Deduction\Manage;

use App\Http\Requests\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return isset($this->user()->employee);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'empl'         => 'required|numeric',
            'type'         => 'required|numeric',
            'amount'       => 'required|numeric',
            'component_id' => 'required|numeric',
            'description'  => 'nullable',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'empl'          => 'karyawan',
            'type'          => 'kategori',
            'amount'        => 'jumlah',
            'component_id'  => 'komponen',
            'description'   => 'deskripsi',
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            ...$this->only('type', 'component_id', 'amount', 'description'),
            'empl_id' => $this->input('empl')
        ];
    }
}
