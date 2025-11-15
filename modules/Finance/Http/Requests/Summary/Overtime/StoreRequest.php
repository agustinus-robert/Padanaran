<?php

namespace Modules\Finance\Http\Requests\Summary\Overtime;

use App\Http\Requests\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'overtimes.*.id'     => 'required|numeric'
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
            'overtimes' => array_values($this->input('overtimes'))
        ]);

        return [
            ...$this->only('start_at', 'end_at'),
            'result' => $this->only('overtimes', 'total_time', 'amount_total')
        ];
    }
}
