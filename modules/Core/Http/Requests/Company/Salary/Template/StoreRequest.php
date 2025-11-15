<?php

namespace Modules\Core\Http\Requests\Company\Salary\Template;

use Illuminate\Support\Str;
use App\Http\Requests\FormRequest;
use Modules\Core\Models\CompanySalarySlipComponent;
use Modules\Core\Models\CompanySalaryTemplate;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', CompanySalaryTemplate::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name'                => 'required|string|max: 191',
            'items.*.slip_az'     => 'nullable|numeric',
            'items.*.slip_name'   => 'nullable|string|max: 191',
            'items.*.ctg_az'      => 'nullable|numeric',
            'items.*.ctg_name'    => 'nullable|string|max: 191',
            'items.*.amount'      => 'nullable|numeric',
            'items.*.description' => 'nullable|string|max: 191',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'name'                => 'nama',
            'items.*.slip_az'     => 'urutan slip',
            'items.*.slip_name'   => 'nama slip',
            'items.*.ctg_az'      => 'urutan kategori',
            'items.*.ctg_name'    => 'nama kategori',
            'items.*.amount'      => 'nominal',
            'items.*.description' => 'deskripsi',
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            ...$this->only('name'),
            'kd' => Str::slug($this->input('name')) . '-' . time(),
            'meta' => [
                'default' => $this->input('as_template') ? true : false,
                'feastday' => $this->input('as_template_feastday') ? true : false,
                'postyear' => $this->input('as_template_postyear') ? true : false,
            ],
            'components' => array_values(array_filter(array_map(fn ($item) => [
                ...$item,
                'component_id'  => $item['component_id'],
                'name'          => CompanySalarySlipComponent::find($item['component_id'])->name ?? '',
                'amount'        => (float) ($item['amount'] ?? 0),
                'description'   => ($item['description'] ?? null),
            ], $this->input('items')), fn ($filter) => $filter['component_id'] != null))
        ];
    }
}
