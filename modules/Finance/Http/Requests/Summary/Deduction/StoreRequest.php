<?php

namespace Modules\Finance\Http\Requests\Summary\Deduction;

use App\Http\Requests\FormRequest;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'fields.*.total'    => 'required|numeric',
            'amount_total'      => 'required|numeric'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'fields.*.total'    => 'total',
            'amount_total'      => 'total keseluruhan'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        $this->merge([
            'deductions' => array_values($this->input('fields'))
        ]);

        return [
            ...$this->only('start_at', 'end_at'),
            'type' => DataRecapitulationTypeEnum::DEDUCTION->value,
            'result' => $this->only('deductions', 'amount_total')
        ];
    }
}
