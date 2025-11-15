<?php

namespace Modules\Finance\Http\Requests\Service\Outwork\Manage;

use App\Http\Requests\FormRequest;
use Modules\Core\Models\CompanyOutworkCategory;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeOutwork;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', EmployeeOutwork::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'empl_id'        => 'required|numeric|exists:' . (new Employee())->getTable() . ',id',
            'name'           => 'required|string',
            'ctg_id'         => 'required|exists:' . (new CompanyOutworkCategory)->getTable() . ',id',
            'dates.d.*'      => 'required|date_format:Y-m-d|before_or_equal:now',
            'dates.s.*'      => 'required|date_format:H:i',
            'dates.e.*'      => 'required|date_format:H:i',
            'dates.b.*'      => 'required|numeric|min:0',
            'description'    => 'nullable|max:400',
            'attachment'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'empl_id'           => 'karyawan',
            'name'              => 'pekerjaan',
            'ctg_id'            => 'kategori izin',
            'dates.d.*'         => 'tanggal',
            'dates.s.*'         => 'jam mulai',
            'dates.e.*'         => 'jam selesai',
            'dates.b.*'         => 'istirahat',
            'description'       => 'deskripsi',
            'attachment'        => 'lampiran'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        $datesInput = $this->input('dates');

        if (is_array($datesInput) && isset($datesInput['d'])) {
            $dates = [];
            foreach ($datesInput['d'] as $index => $date) {
                $dates[] = array_filter([
                    'd' => $date,
                    't_s' => $datesInput['s'][$index] ?? null,
                    't_e' => $datesInput['e'][$index] ?? null,
                    'b' => $datesInput['b'][$index] ?? null,
                    'p' => (bool) $this->input('prepare')
                ]);
            }
        } else {
            $dates = [];
        }

        return array_merge(
            $this->only('empl_id', 'name', 'ctg_id', 'description'),
            [
                'dates' => $dates,
                'attachment' => $this->handleUploadedFile()
            ]
        );
    }

    /**
     * Handle uploaded file
     */
    public function handleUploadedFile()
    {
        return $this->hasFile('attachment') ? $this->file('attachment')->store('users/' . $this->user()->id . '/employees/outworks') : null;
    }
}
