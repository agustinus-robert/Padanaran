<?php

namespace Modules\Finance\Http\Requests\Service\Loan\Transaction;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\HRMS\Enums\LoanMethodEnum;
use Modules\HRMS\Enums\LoanCashTypeEnum;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeLoanInstallment;
use Carbon\Carbon;

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
            'installment'    => 'required|exists:' . (new EmployeeLoanInstallment())->getTable() . ',id',
            'method'         => ['required', new Enum(LoanMethodEnum::class)],
            'is_cash'        => ['required', new Enum(LoanCashTypeEnum::class)],
            'amount'         => 'required|numeric|min:0',
            'payer_id'       => 'required|exists:' . (new Employee())->getTable() . ',id',
            'recipient_id'   => 'required|exists:' . (new Employee())->getTable() . ',id',
            'paid_at'        => 'required|date',
            'meta'           => 'nullable'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'installment'    => 'pinjaman',
            'method'         => 'metode pembayaran',
            'is_cash'        => 'alat pembayaran',
            'amount'         => 'nominal',
            'payer_id'       => 'karyawan',
            'recipient_id'   => 'penerima',
            'paid_at'        => 'tanggal pembayaran',
            'meta'           => 'meta data'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        $paid_at = Carbon::parse($this->input('paid_at'));

        return [
            ...$this->only('method', 'is_cash', 'amount', 'payer_id', 'recipient_id', 'meta'),
            'installment_id' => $this->input('installment'),
            'paid_at' => $paid_at,
        ];
    }
}
