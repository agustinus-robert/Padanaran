<?php

namespace Modules\HRMS\Http\Requests\Service\Reimbursement\Manage;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Models\CompanyReimbursementCategory;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'transaction_at'    => 'required|date_format:Y-m-d\TH:i|before_or_equal:now',
            'amount'            => 'required|numeric',
            'method'            => 'required|in:' . implode(',', CompanyReimbursementCategory::find($this->input('ctg_id'))->meta->method),
            'description'       => 'nullable|max:191',
            'file'              => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'transaction_at'    => 'tanggal transaksi',
            'amount'            => 'jumlah/nominal',
            'method'            => 'metode pembayaran',
            'description'       => 'deskripsi',
            'file'              => 'lampiran'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            ...$this->only('transaction_at', 'ctg_id', 'amount', 'method', 'description'),
            'attachment' => $this->handleUploadedFile()
        ];
    }

    /**
     * Handle uploaded file
     */
    public function handleUploadedFile()
    {
        if ($this->has('file')) {
            Storage::delete($this->reimbursement->attachment);
            return $this->file('file')->store('employee/reimbursements/attachments');
        }

        return $this->reimbursement->attachment;
    }
}
