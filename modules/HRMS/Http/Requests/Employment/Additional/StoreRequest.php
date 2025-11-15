<?php

namespace Modules\HRMS\Http\Requests\Employment\Additional;

use App\Http\Requests\FormRequest;
use Modules\Core\Models\CompanyPosition;
use Modules\HRMS\Models\Employee;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', Employee::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'additional_possition' => 'required|string|max:191|exists:' . (new CompanyPosition())->getTable() . ',id',
            'start_at'             => 'sometimes|date',
            'end_at'               => 'nullable|date|after:start_at'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'additional_possition' => 'nama jabatan',
            'start_at'             => 'tanggal berlaku',
            'end_at'               => 'tanggal berakhir'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            'additional_days' => (int)$this->input('additional_days'),
            'additional_possition' => (int)$this->input('additional_possition'),
            'additional_range' => [
                'start_at' => $this->input('start_at'),
                'end_at' => $this->input('end_at') ?? null,
            ]
        ];
    }
}
