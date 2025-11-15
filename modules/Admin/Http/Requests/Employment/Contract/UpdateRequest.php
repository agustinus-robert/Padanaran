<?php

namespace Modules\Admin\Http\Requests\Employment\Contract;

use Illuminate\Validation\Rules\Enum;
use App\Http\Requests\FormRequest;
use App\Models\Contract;
use App\Enums\WorkLocationEnum;
use Modules\Account\Models\Employee;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->contract);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'employee_id'       => 'required|exists:' . (new Employee())->getTable() . ',id',
            'contract_id'       => 'required|exists:' . (new Contract())->getTable() . ',id',
            'work_location'     => ['required', new Enum(WorkLocationEnum::class)],
            'kd'                => 'required|string|max:191',
            'start_at'          => 'required|date|before:end_at',
            'end_at'            => 'nullable|date|after:start_at',
            'contract_file'     => 'nullable|file|mimes:pdf|max:2048'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'employee_id'       => 'karyawan',
            'contract_id'       => 'jenis perjanjian kerja',
            'work_location'     => 'lokasi kerja',
            'kd'                => 'nomor perjanjian kerja',
            'start_at'          => 'tanggal berlaku',
            'end_at'            => 'tanggal berakhir',
            'contract_file'     => 'dokumen perjanjian kerja'
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
