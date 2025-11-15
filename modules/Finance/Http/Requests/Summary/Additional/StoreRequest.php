<?php

namespace Modules\Finance\Http\Requests\Summary\Additional;

use App\Http\Requests\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'additionals.*.id'     => 'required|numeric'
        ];
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
        $this->merge([
            'additionals' => array_values($this->input('additionals'))
        ]);

        return [
            ...$this->only('start_at', 'end_at'),
            'result' => $this->only('additionals', 'total_time', 'amount_total')
        ];
    }
}
