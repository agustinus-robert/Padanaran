<?php

namespace Modules\HRMS\Http\Requests\Service\Leave\Manage;

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
            'dates.*'           => 'required|date_format:Y-m-d|distinct',
            'time_start'        => 'nullable|date_format:H:i|before:time_end',
            'time_end'          => 'nullable|date_format:H:i|after:time_start',
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
            'dates.*'           => 'tanggal',
            'time_start'        => 'waktu mulai',
            'time_end'          => 'waktu selesai',
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
        foreach ($this->input('dates') as $i => $date) {
            $dates[] = array_filter([
                'd' => $date,
                't_s' => $this->input('time_start'),
                't_e' => $this->input('time_end')
            ]);
        }

        return array_filter([
            'dates' => $dates,
            'attachment' => $this->handleUploadedFile(),
            'description' => $this->input('description')
        ]);
    }

    /**
     * Handle uploaded file
     */
    public function handleUploadedFile()
    {
        if ($this->file('attachment')) {
            if ($this->leave->attachment) {
                Storage::delete($this->leave->attachment);
            }
            return $this->file('attachment')->store('employee/leaves/attachments');
        }

        return null;
    }
}
