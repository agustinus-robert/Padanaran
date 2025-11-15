<?php

namespace Modules\HRMS\Http\Requests\Service\Attendance\Schedule;

use App\Http\Requests\FormRequest;
use Modules\HRMS\Models\Employee;
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
            'empl_id'           => 'required|exists:' . (new Employee())->getTable() . ',id',
            'month'             => 'required|date_format:Y-m|exclude',
            'dates.*.*.*'       => 'nullable',
            'workdays_count'    => 'required|numeric'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'empl_id'           => 'karyawan',
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
        $categoryLesson = $this->input('category_lesson');

        foreach ($this->input('dates') as $date => $shifts) {
            foreach ($shifts as $subjectArray) {
                $subjectFilled = is_array($subjectArray) && count(array_filter($subjectArray)) > 0;

                $dates[$date][] = array_merge(
                    $subjectFilled ? array_filter($subjectArray) : [null],
                    ['type' => $categoryLesson]
                );
            }
        }

        return array_merge($this->only('empl_id', 'workdays_count'), [
            'dates' => $dates,
            'start_at' => date('Y-m-01', strtotime($this->input('month'))),
            'end_at' => date('Y-m-t', strtotime($this->input('month'))),
        ]);
    }
}
