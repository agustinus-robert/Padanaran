<?php

namespace Modules\HRMS\Http\Requests\Summary\Attendance;

use App\Http\Requests\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'summary.*.*.*'       => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'summary.days'                  => 'jumlah hari',
            'summary.workdays'              => 'hari efektif',
            'summary.holidays'              => 'hari libur nasional',
            'summary.attendance_total'      => 'jumlah presensi',
            'summary.ontime_total'          => 'jumlah tepat waktu',
            'summary.late_total'            => 'jumlah terlambat',
            'summary.presence.wfo'          => 'presensi WFO',
            'summary.presence.wfa'          => 'presensi WFA',
            'summary.presence.move'         => 'jumlah WFO ke WFA',
            'summary.ontime.wfo'            => 'presensi WFO ontime',
            'summary.ontime.wfa'            => 'presensi WFA ontime',
            'summary.ontime.move'           => 'jumlah WFA ke WFO ontime',
            'summary.unpresence.leave'      => 'jumlah izin',
            'summary.unpresence.vacation.*' => 'jumlah cuti',
            'summary.unpresence.cashable_vacation' => 'jumlah kompensasi cuti',
            'summary.overtime.works'        => 'jumlah lembur di luar jam kerja',
            'summary.overtime.overdays'     => 'jumlah lembur kelebihan hari',
            'summary.overtime.holidays'     => 'jumlah lembur tanggal merah',
            'summary.overtime.total'        => 'jumlah lembur keseluruhan',
            'summary.attendance_duty'       => 'Piket',
            'summary.attendance_pat'        => 'PAT',
            'summary.attendance_ukm'        => 'UKM',
            'summary.attendance_invigilator' => 'Pengawas',
        ];
    }

    /**
     * Map to float values.
     */
    public function mapFloat(array $items)
    {
        foreach ($items as $key => $item) {
            $result[$key] = is_array($item) ? $this->mapFloat($item) : (float) $item;
        }
        return $result ?? [];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            ...$this->only('start_at', 'end_at'),
            'result' => array_merge(
                $this->mapFloat($this->input('summary')),
                ['original' => json_decode($this->input('original'), true)]
            ),
        ];
    }
}
