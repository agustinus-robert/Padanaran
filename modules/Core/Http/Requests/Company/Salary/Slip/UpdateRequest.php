<?php

namespace Modules\Core\Http\Requests\Company\Salary\Slip;

use Modules\Core\Models\CompanySalarySlip;

class UpdateRequest extends StoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->slip);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'az'   => 'required|unique:'.(new CompanySalarySlip)->getTable().',az,'.$this->slip->id.'|numeric|between:1,200',
            'name' => 'required|max:191|string'
        ];
    }
}