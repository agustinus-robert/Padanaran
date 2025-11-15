<?php

namespace Modules\HRMS\Http\Requests\Service\Attendance\Scanlog;

use App\Http\Requests\FormRequest;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeScanLog;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', EmployeeScanLog::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'employee'       => 'required|exists:' . (new Employee())->getTable() . ',id',
            'datetime'       => 'required|date'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'employee'       => 'karyawan',
            'datetime'       => 'tanggal dan waktu'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            'location' => $this->input('location'),
            'ip' => $this->ip(),
            'latlong' => [],
            'user_agent' => $this->server('HTTP_USER_AGENT'),
            'created_at' => $this->input('datetime'),
        ];
    }
}
