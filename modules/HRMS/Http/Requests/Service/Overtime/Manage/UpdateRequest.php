<?php

namespace Modules\HRMS\Http\Requests\Service\Overtime\Manage;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Storage;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name'              => 'required|string|max:191',
            'dates.d.*'         => 'required|date_format:Y-m-d|before_or_equal:now',
            'dates.s.*'         => 'required|date_format:H:i',
            'dates.e.*'         => 'required|date_format:H:i',
            'description'       => 'nullable|max:191',
            'attachment'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'name'              => 'pekerjaan',
            'dates.d.*'         => 'tanggal',
            'dates.s.*'         => 'jam mulai',
            'dates.e.*'         => 'jam selesai',
            'description'       => 'deskripsi',
            'attachment'        => 'lampiran'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        $dates = [];

        foreach ($this->input('dates.d', []) as $index => $date) {
            $dates[] = array_filter([
                'd' => $date,
                't_s' => $this->input('dates.s.' . $index),
                't_e' => $this->input('dates.e.' . $index)
            ]);
        }

        return array_merge(
            $this->only('name', 'description'),
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
        if ($this->hasFile('file')) {
            if (!empty($this->overtime->attachment)) {
                Storage::delete($this->overtime->attachment);
            }
            return $this->file('file')->store('employee/overtimes/attachments');
        }

        return $this->overtime->attachment;
    }
}
