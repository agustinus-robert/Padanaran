<?php

namespace Modules\Poz\Http\Requests;

use Auth;
use App\Http\Requests\FormRequest;


class ProductStoreRequest extends FormRequest
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
            'form.type' => 'required',
            'form.code' => 'required',
            'form.name' => 'required',
            'form.barcode' => 'required',
            'form.brand_id' => 'required',
            'form.category_id' => 'required',
            'form.unit_id' => 'required',
            'form.tax_rate_id' => 'required',
            'form.price' => 'required',
            'form.wholesale' => 'required',
            'form.document' => 'nullable',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'form.type' => 'Tipe',
            'form.code' => 'Kode',
            'form.name' => 'Nama Produk',
            'form.barcode' => 'Barcode',
            'form.brand_id' => 'Brand',
            'form.category_id' => 'Kategori',
            'form.unit_id' => 'Unit',
            'form.tax_rate_id' => 'Pajak',
            'form.wholesale' => 'Harga Beli',
            'form.price' => 'Harga',
        ];
    }

    public function messages(): array
    {
        return [
            'form.type.required' => ':attribute harus diisi',
            'form.code.required' => ':attribute harus diisi',
            'form.name.required' => ':attribute harus diisi',
            'form.barcode.required' => ':attribute harus diisi',
            'form.brand_id.required' => ':attribute harus diisi',
            'form.category_id.required' => ':attribute harus diisi',
            'form.unit_id.required' => ':attribute harus diisi',
            'form.tax_rate_id.required' => ':attribute harus diisi',
            'form.wholesale.required' => ':attribute harus diisi',
            'form.price.required' => ':attribute harus diisi',
        ];
    }
}
