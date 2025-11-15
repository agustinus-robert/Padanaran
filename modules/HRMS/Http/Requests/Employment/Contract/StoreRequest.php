<?php

namespace Modules\HRMS\Http\Requests\Employment\Contract;

use Illuminate\Validation\Rules\Enum;
use App\Http\Requests\FormRequest;
use Modules\Core\Models\CompanyContract;
use Modules\Core\Enums\WorkLocationEnum;
use Modules\HRMS\Models\Employee;
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
            'employee_id'       => 'required|exists:' . (new Employee())->getTable() . ',id',
            'contract_id'       => 'required|exists:' . (new CompanyContract())->getTable() . ',id',
            'work_location'     => ['required', new Enum(WorkLocationEnum::class)],
            'kd'                => 'required|string|max:191|unique:' . (new EmployeeContract())->getTable() . ',kd',
            'start_at'          => 'required',
            'end_at'            => 'nullable',
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
