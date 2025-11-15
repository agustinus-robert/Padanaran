<?php

namespace Modules\HRMS\Http\Requests\Service\Attendance\Collective;

use App\Http\Requests\FormRequest;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeContract;
use Modules\HRMS\Models\EmployeeSchedule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', EmployeeSchedule::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'empl_ids.*'        => 'required|exists:' . (new Employee())->getTable() . ',id',
            'month'             => 'required|date_format:Y-m|exclude',
            'dates.*.*.*'       => 'nullable|date_format:H:i',
           'workdays_count'    => 'required|numeric'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'empl_ids.*'        => 'karyawan',
            'month'             => 'periode',
            'dates.*'           => 'waktu',
            'dates.*.*'         => 'waktu',
            'dates.*.*.*'       => 'waktu',
            'workdays_count'    => 'hari efektif',
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        $dates = [];
        foreach ($this->input('dates') as $date => $shifts) {
            foreach ($shifts as $times) {
                $filled = is_array($times) && count(array_filter($times)) == 2;
                $dates[$date][] = [
                    $filled ? ($date . ' ' . $times[0] . ':00') : null,
                    $filled ? ((strtotime($times[1]) < strtotime($times[0])) ? date('Y-m-d', strtotime($date . ' +1 days')) . ' ' . $times[1] . ':00' : $date . ' ' . $times[1] . ':00') : null
                ];
            }
        }

        return array_map(fn($id) => array_merge($this->only('workdays_count'), [
            'empl_id' => $id,
            'dates' => json_encode($dates),
            'start_at' => date('Y-m-01', strtotime($this->input('month'))),
            'end_at' => date('Y-m-t', strtotime($this->input('month')))
        ]), $this->input('empl_ids'));
    }
}
