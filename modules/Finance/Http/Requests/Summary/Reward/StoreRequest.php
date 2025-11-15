<?php

namespace Modules\Finance\Http\Requests\Summary\Reward;

use App\Http\Requests\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'type'         => 'required|numeric',
            'amount_total' => 'required|numeric',
            'fields'       => 'nullable',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'type'         => 'kategori',
            'amount_total' => 'subtotal',
            'fields'       => 'detail',
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        $this->merge([
            'reward' => array_values($this->input('fields'))
        ]);

        return [
            ...$this->only('type', 'start_at', 'end_at'),
            'result' => $this->only('reward', 'amount_total')
        ];
    }
}
