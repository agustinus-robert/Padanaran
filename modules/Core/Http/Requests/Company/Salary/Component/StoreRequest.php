<?php

namespace Modules\Core\Http\Requests\Company\Salary\Component;

use Str;
use App\Http\Requests\FormRequest;
use Modules\Core\Models\CompanySalarySlipCategory;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', CompanySalarySlipComponent::class);
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

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'ctg_id'  => 'kategori',
            'unit'    => 'satuan',
            'name'    => 'nama komponen',
            'operate' => 'jenis'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            ...$this->only('slip_id', 'ctg_id', 'unit', 'name', 'operate'),
            'kd' => Str::slug($this->input("name") . '-' . time()),
            'slip_id' => CompanySalarySlipCategory::find($this->input('ctg_id'))->slip_id
        ];
    }
}
