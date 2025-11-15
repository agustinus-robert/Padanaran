<?php

namespace Modules\Finance\Http\Requests\Payroll\Template;

use Modules\HRMS\Models\Employee;
use App\Http\Requests\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->template);
    }

    public function rules()
    {
        return [
            'employee'            => 'required|exists:' . (new Employee())->getTable() . ',id',
            'name'                => 'required|string|max: 191',
            'prefix'              => 'required|string|max:191',
            'start_at'            => 'required',
            'end_at'              => 'required',
            'items.*.slip_az'     => 'nullable|numeric',
            'items.*.slip_name'   => 'nullable|string|max: 191',
            'items.*.ctg_az'      => 'nullable|numeric',
            'items.*.ctg_name'    => 'nullable|string|max: 191',
            'items.*.ctg_id'      => 'nullable|numeric',
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
            'employee'            => 'nama karyawan',
            'name'                => 'periode',
            'prefix'              => 'prefix',
            'start_at'            => 'tanggal berlaku',
            'end_at'              => 'tanggal berakhir',
            'items.*.slip_az'     => 'urutan slip',
            'items.*.slip_name'   => 'nama slip',
            'items.*.ctg_az'      => 'urutan kategori',
            'items.*.ctg_name'    => 'nama kategori',
            'items.*.ctg_id'      => 'komponen',
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
            ...$this->only('name', 'prefix', 'start_at', 'end_at'),
            'items' => array_map(fn($item) => [
                ...$item,
                'component_id'  => $item['component_id'],
                'amount'        => (float) ($item['amount'] ?? 0),
                'description'   => ($item['description'] ?? null),
            ], array_filter($this->input('items'), fn($item) => isset($item['component_id'])))
        ];
    }
}
