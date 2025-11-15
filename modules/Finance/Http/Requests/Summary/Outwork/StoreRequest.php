<?php

namespace Modules\Finance\Http\Requests\Summary\Outwork;

use App\Http\Requests\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'outworks.*.id'     => 'required|numeric'
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
            'outworks' => array_values($this->input('outworks'))
        ]);

        return [
            ...$this->only('start_at', 'end_at'),
            'result' => $this->only('outworks', 'amount_total')
        ];
    }
}
