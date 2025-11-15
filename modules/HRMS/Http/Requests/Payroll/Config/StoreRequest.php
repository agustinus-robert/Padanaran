<?php

namespace Modules\HRMS\Http\Requests\Payroll\Config;

use App\Http\Requests\FormRequest;

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
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            'key' => $this->input('key'),
            'meta' => [
                'config' => '1',
                'default_component' => $this->input('default_component'),
                'component' => $this->input('component'),
                'calculation' => $this->input('calculation')
            ]
        ];
    }
}
