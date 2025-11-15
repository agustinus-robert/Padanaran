<?php

namespace Modules\HRMS\Http\Requests\Summary\Vacation;

use App\Http\Requests\FormRequest;
use Carbon\Carbon;
use Modules\Core\Models\CompanyVacationCategory;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Models\EmployeeDataRecapitulation;
use Modules\HRMS\Models\EmployeeVacationQuota;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', EmployeeDataRecapitulation::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        $quota = EmployeeVacationQuota::with('vacations')->firstWhere('id', $this->input('quota_id'));
        return [
            'ctg_id'   => 'required|exists:' . (new CompanyVacationCategory())->getTable() . ',id',
            'quota_id' => 'required|exists:' . (new EmployeeVacationQuota())->getTable() . ',id',
            'days'     => 'required|max:' . ($quota->remain < $quota->category->meta->cashable_limit ? $quota->remain : $quota->category->meta->cashable_limit)
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'ctg_id'   => 'jenis cuti',
            'quota_id' => 'kuota cuti',
            'days'     => 'jumlah hari'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        $cutoff = cmp_cutoff(1)->gt(today()) ? cmp_cutoff(1) : cmp_cutoff(1)->addMonths(1);

        $dates = [];

        for ($i = 0; $i < $this->input('days'); $i++) {
            $dates[] = array_filter([
                'd' => '2022-12-10',
                'cashable' => true
            ]);
        }

        $quota = EmployeeVacationQuota::with('vacations')->firstWhere('id', $this->input('quota_id'));

        return [
            'vacation' => [
                'quota_id'    => $quota->id,
                'description' => 'Kompensasi ' . $quota->category->name,
                'dates'       => $dates
            ],
            'recaps' => [
                'type'     => DataRecapitulationTypeEnum::CASHABLE_VACATION,
                'start_at' => Carbon::parse($this->get('start_at'))->format('Y-m-d'),
                'end_at'   => Carbon::parse($this->get('end_at'))->format('Y-m-d'),
                'result'   => [
                    'days'    => $this->input('days'),
                    'price'   => $this->input('price'),
                    'quota'   => $quota
                ]
            ]
        ];
    }
}
