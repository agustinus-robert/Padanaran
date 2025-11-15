<?php

namespace Modules\Administration\Http\Requests\Service\Teacher;

use App\Http\Requests\FormRequest;

class UpdateRequest extends FormRequest
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
            'code'  => 'required|string',
            'default_workhour'  => 'required|numeric',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'code'  => 'kode',
            'default_workhour'  => 'beban mengajar',
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            'code' => $this->input('code'),
            'default_workhour' => (int) $this->input('default_workhour')
        ];
    }
}
