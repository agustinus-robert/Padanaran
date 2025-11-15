<?php

namespace Modules\Core\Http\Requests\Company\Salary\Component;

use Modules\Core\Models\CompanySalarySlipCategory;

class UpdateRequest extends StoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->component);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'ctg_id'  => 'required|numeric|exists:' . (new CompanySalarySlipCategory)->getTable() . ',id',
            'unit'    => 'required|numeric',
            'name'    => 'required|max:191|string',
            'operate' => 'nullable'
        ];
    }
}
