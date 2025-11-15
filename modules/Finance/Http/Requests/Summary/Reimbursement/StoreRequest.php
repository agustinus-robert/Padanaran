<?php

namespace Modules\Finance\Http\Requests\Summary\Reimbursement;

use App\Http\Requests\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'reimbursements.*.total'     => 'required|numeric'
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
            'reimbursements' => array_values($this->input('reimbursements'))
        ]);

        return [
            ...$this->only('start_at', 'end_at'),
            'result' => $this->only('reimbursements', 'amount_total')
        ];
    }
}
