<?php

namespace Modules\HRMS\Http\Requests\Service\Vacation\Quota;

use App\Http\Requests\FormRequest;
use Modules\Core\Models\CompanyVacationCategory;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeVacationQuota;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', EmployeeVacationQuota::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'employee'          => 'required|exists:' . (new Employee())->getTable() . ',id',
            'quotas.category.*' => 'required|exists:' . (new CompanyVacationCategory())->getTable() . ',id',
            'quotas.start_at.*' => 'required|date_format:Y-m-d',
            'quotas.end_at.*'   => 'nullable|date_format:Y-m-d',
            'quotas.quota.*'    => 'nullable|numeric|max:365',
            'visible_at'        => 'nullable'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'employee'          => 'karyawan',
            'quotas.category.*' => 'kategori',
            'quotas.start_at.*' => 'masa berlaku',
            'quotas.end_at.*'   => 'masa berlaku',
            'quotas.quota.*'    => 'kuota',
            'visible_at'        => 'mulai berlaku'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        $input = $this->input('quotas');

        $quotas = [];
        for ($i = 0; $i < count($input['category'] ?? []); $i++) {
            $quotas[] = [
                'ctg_id' => $input['category'][$i],
                'start_at' => $input['start_at'][$i],
                'end_at' => $input['end_at'][$i],
                'quota' => $input['quota'][$i],
                'visible_at' => $this->input('visible_at')
            ];
        }

        return [
            'empl_id' => $this->input('employee'),
            'grade_id' => userGrades(),
            'quotas' => $quotas
        ];
    }
}
