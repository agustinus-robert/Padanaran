<?php

namespace Modules\Core\Http\Requests\Company\Salary\Category;

use App\Http\Requests\FormRequest;
use Modules\Core\Models\CompanySalarySlip;
use Modules\Core\Models\CompanySalarySlipCategory;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', CompanySalarySlipCategory::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'az'        => 'required|unique:' . (new CompanySalarySlipCategory)->getTable() . ',az|numeric|between:1,200',
            'slip_id'   => 'required|numeric|exists:' . (new CompanySalarySlip)->getTable() . ',id',
            'name'      => 'required|max:191|string'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'az'        => 'urutan',
            'slip_id'   => 'kategori slip',
            'name'      => 'nama kategori'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return $this->validated();
    }
}
