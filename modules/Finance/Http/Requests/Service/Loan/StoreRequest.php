<?php

namespace Modules\Finance\Http\Requests\Service\Loan;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Core\Enums\LoanTenorEnum;
use Modules\Core\Models\CompanyLoanCategory;
use Modules\HRMS\Models\Employee;
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
            'employee'          => 'required|exists:' . (new Employee())->getTable() . ',id',
            'ctg_id'            => 'required|exists:' . (new CompanyLoanCategory())->getTable() . ',id',
            'description'       => 'nullable|string',
            'amount'            => 'required|numeric|min:0',
            'tenor'             => 'required|numeric|min:0',
            'tenor_by'          => ['required', new Enum(LoanTenorEnum::class)],
            'submission_at'     => 'required|date',
            'start_at'          => 'required|date',
            'file'              => 'nullable'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'employee'          => 'karyawan',
            'ctg_id'            => 'kategori',
            'description'       => 'deskripsi',
            'amount'            => 'nominal',
            'tenor'             => 'tenor',
            'tenor_by'          => 'jenis tenor',
            'submission_at'     => 'tanggal pengajuan',
            'start_at'          => 'tanggal tagihan',
            'file'              => 'lampiran'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        $category = CompanyLoanCategory::find($this->input('ctg_id'));

        $tenor = LoanTenorEnum::tryFrom($this->input('tenor_by'));
        $start_at = Carbon::parse($this->input('start_at'));

        return [
            ...$this->only('description', 'tenor', 'tenor_by', 'submission_at', 'start_at'),
            'empl_id' => $this->input('employee'),
            'amount_total' => $this->input('amount'),
            'ctg_id' => $category->id,
            'approved_at' => now(),
            'installments' => $this->getInstallments($category, $this->input('amount'), $tenor, $start_at),
            'interest' => $this->getInterestByCategory($category->interest, $tenor, $start_at),
            'meta' => [
                'agreement' => $this->handleUploadedFile(),
            ]
        ];
    }

    /**
     * Get interest by category.
     */
    public function getInterestByCategory(CompanyLoanCategory $category, $tenor, $start_at)
    {
        return $category->id && $this->input('has_interest') ? [
            ...$this->only('description', 'tenor', 'tenor_by', 'submission_at', 'start_at'),
            'empl_id' => $this->input('employee'),
            'amount_total' => ($amount = $this->input('amount') * $this->input('tenor') * $this->input('interest_value') / 100),
            'ctg_id' => $category->id,
            'approved_at' => now(),
            'installments' => $this->getInstallments($category, $amount, $tenor, $start_at),
            'meta' => [
                'agreement' => $this->handleUploadedFile(),
            ]
        ] : null;
    }

    /**
     * Get installments.
     */
    public function getInstallments(CompanyLoanCategory $category, $amount, $tenor, $start_at)
    {
        $installments = [];
        for ($i = 0; $i < $this->input('tenor'); $i++) {
            array_push($installments, [
                'bill_at' => $start_at->clone()->{$tenor->operator('Carbon')}($i)->format('Y-m-d'),
                'amount' => $amount / (isset($category->meta?->multiplied_by_tenor) ? 1 : $this->input('tenor'))
            ]);
        }

        return $installments;
    }

    public function handleUploadedFile()
    {
        return $this->has('file') ? $this->file('file')->store('users/' . $this->user()->id . '/employees/loan/agreement') : null;
    }
}
