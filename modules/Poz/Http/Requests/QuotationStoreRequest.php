<?php

namespace Modules\Poz\Http\Requests;

use Auth;
use App\Http\Requests\FormRequest;


class QuotationStoreRequest extends FormRequest
{
    public $placeable;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', Inquiry::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'form.outletId' => 'required',
            'form.payment_on' => 'required',
            
            'rows'                => 'required|array|min:1',
            'rows.*.name'         => 'required|string|max:255',
            'rows.*.price'        => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'form.outletId' => 'Nama Outlet',
            'form.payment_on' => 'Tipe pembayaran',
            'rows.*.name'     => 'Nama item',
            'rows.*.price'    => 'Harga item',
        ];
    }

    public function messages(): array
    {
        return [
            'form.outletId.required' => ':attribute harus diisi',
            'form.payment_on.required' => ':attribute harus diisi',
            'rows.required'            => 'Minimal 1 item harus ditambahkan',
            'rows.*.name.required'     => ':attribute harus diisi',
            'rows.*.price.required'    => ':attribute harus diisi',
            'rows.*.file.required'     => ':attribute harus diisi'
        ];
    }
}
