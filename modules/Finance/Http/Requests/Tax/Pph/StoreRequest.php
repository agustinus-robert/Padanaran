<?php

namespace Modules\Finance\Http\Requests\Tax\Pph;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\HRMS\Enums\TaxTypeEnum;
use Modules\HRMS\Models\Employee;

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
            'empl_id'        => 'required|exists:' . (new Employee())->getTable() . ',id',
            'type'           => ['required', new Enum(TaxTypeEnum::class)],
            'start_at'       => 'required|date',
            'end_at'         => 'required|date',
            'pkp'            => 'nullable|numeric',
            'category'       => 'nullable',
            'ter_category'   => 'nullable',
            'rate'           => 'nullable|numeric',
            'amount'         => 'nullable|numeric',
            'pphtotal'       => 'nullable|numeric',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'employee'       => 'karyawan',
            'type'           => 'tipe penghitungan',
            'start_at'       => 'tanggal mulai',
            'end_at'         => 'tanggal selesai',
            'pkp'            => 'pendapatan',
            'category'       => 'status',
            'ter_category'   => 'TER kategori',
            'rate'           => 'prosentase tarif',
            'amount'         => 'jumlah pajak terhutang',
            'pphtotal'       => 'pajak terhutang',
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            'empl_id'   => $this->empl_id,
            'start_at'  => $this->input('start_at'),
            'end_at'    => $this->input('end_at'),
            'type'      => (TaxTypeEnum::MONTHLY)->value,
            'meta'      => [
                'pkp'          => $this->input('pkp'),
                'category'     => $this->input('category'),
                'ter_category' => $this->input('ter_category'),
                'rate'         => $this->input('rate'),
                'amount'       => $this->input('amount'),
                'pphtotal'     => $this->input('pphtotal'),
            ]
        ];
    }
}
