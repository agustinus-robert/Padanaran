<?php

namespace Modules\Finance\Http\Requests\Benefit\Insurance;

use App\Http\Requests\FormRequest;
use Modules\Core\Models\CompanyInsurancePrice;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeInsurance;

class StoreRequest extends FormRequest
{
    public $placeable;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', EmployeeInsurance::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'employee'                      => 'required|exists:' . (new Employee())->getTable() . ',id',
            'insurances.*.price_id'         => 'required|exists:' . (new CompanyInsurancePrice())->getTable() . ',id',
            'insurances.*.cmp_price'        => 'required|numeric',
            'insurances.*.empl_price'       => 'required|numeric',
            'insurances.*.meta.cmp_price'   => 'nullable|numeric',
            'insurances.*.meta.cmp_factor'  => 'nullable|numeric',
            'insurances.*.meta.empl_price'  => 'nullable|numeric',
            'insurances.*.meta.empl_factor' => 'nullable|numeric',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'employee'                      => 'nama karyawan',
            'insurances.*.price_id'         => 'jenis asuransi',
            'insurances.*.cmp_price'        => 'tarif iuran perusaaan',
            'insurances.*.empl_price'       => 'tarif iuran karyawan',
            'insurances.*.meta.cmp_price'   => 'persentase perusahaan',
            'insurances.*.meta.cmp_factor'  => 'tarif perusahaan',
            'insurances.*.meta.empl_price'  => 'persentase karyawan',
            'insurances.*.meta.empl_factor' => 'tarif perusahaan',
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            'employee' => $this->input('employee'),
            'insurances' => array_map(fn($insurance) => [
                'price_id'   => $insurance['price_id'],
                'cmp_price'  => (float) $insurance['cmp_price'],
                'empl_price' => (float) $insurance['empl_price'],
                'meta'       => array_map(fn($value) => is_numeric($value) ? (float) $value : $value, $insurance['meta'])
            ], $this->input('insurances'))
        ];
    }
}
