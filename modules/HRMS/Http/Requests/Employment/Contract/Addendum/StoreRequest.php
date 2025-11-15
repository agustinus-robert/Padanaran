<?php

namespace Modules\HRMS\Http\Requests\Employment\Contract\Addendum;

use Illuminate\Validation\Rules\Enum;
use App\Http\Requests\FormRequest;
use Modules\Core\Enums\WorkLocationEnum;
use Modules\HRMS\Models\EmployeeContract;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', EmployeeContract::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'addendum_kd'       => 'required|string|max:191',
            'start_at'          => 'required',
            'end_at'            => 'nullable',
            'addendum_file'     => 'nullable',
            'work_location'     => ['nullable', new Enum(WorkLocationEnum::class)],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'addendum_kd'       => 'nomor adendum',
            'start_at'          => 'tanggal mulai',
            'end_at'            => 'tanggal selesai',
            'addendum_file'     => 'lampiran',
            'work_location'     => 'lokasi kerja',
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            'addendum' => [
                'employee_id'   => $this->contract->employee->id,
                'contract_id'   => $this->input('contract_id'),
                'kd'            => $this->contract->kd,
                'addendum_file' => $this->handleUploadedFile(),
                'addendum_kd'   => $this->input('addendum_kd'),
                'position_id'   => $this->input('position_id'),
                'start_at'      => $this->input('start_at'),
                'end_at'        => $this->input('end_at'),
                'work_location' => $this->input('work_location'),
                'old_contract'  => $this->contract->load('position'),
            ],
            'position' => [
                'empl_id'       => $this->contract->employee->id,
                'position_id'   => $this->position_id,
                'start_at'      => $this->start_at,
                'end_at'        => $this->end_at
            ]
        ];
    }

    /**
     * Handle uploaded file
     */
    public function handleUploadedFile()
    {
        return $this->has('files') ? $this->file('files')->store('users/' . $this->employee->user_id . '/employees/addendums') : null;
    }
}
