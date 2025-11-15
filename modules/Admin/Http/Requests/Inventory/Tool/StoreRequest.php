<?php

namespace Modules\Admin\Http\Requests\Inventory\Tool;

use Auth;
use App\Http\Requests\FormRequest;
use Modules\Core\Enums\PlaceableTypeEnum;
use Modules\Core\Models\Tool;

class StoreRequest extends FormRequest
{
    public $placeable;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', Supplier::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'form.name_asset' => 'required',
            'form.kategori' => 'required',
            'form.unit' => 'required',
            'form.material' => 'required|string',
            'form.year_buy' => 'required|numeric',
            'form.price' => 'required|numeric',
            'form.qty' => 'required|numeric',
            'form.origin' => 'required',
            'form.address_primary' => 'required',
            'form.information' => 'required',
            'form.conditional' => 'required',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'form.name_asset' => 'Nama aset',
            'form.kategori' => 'Kategori',
            'form.unit' => 'Unit',
            'form.material' => 'Bahan',
            'form.year_buy' => 'Tahun Pembelian',
            'form.price' => 'Harga',
            'form.qty' => 'Jumlah',
            'form.origin' => 'Asal Usul',
            'form.address_primary' => 'Alamat',
            'form.information' => 'Informasi',
            'form.conditional' => 'Kondisi',
        ];
    }

    public function messages(): array {
         return [
            'form.name_asset.required' => 'Nama aset harus diisi',
            'form.kategori.required' => 'Kategori Harus dipilih',
            'form.unit.required' => 'Unit Harus dipilih',
            'form.material.required' => 'Bahan harus diisi',
            'form.material.string' => 'Bahan harus berisi string',
            'form.year_buy.required' => 'Tahun Pembelian harus diisi',
            'form.year_buy.numeric' => 'Tahun Pembelian harus berisi angka',
            'form.price.required' => 'Harga barang harus diisi',
            'form.price.numeric' => 'Harga barang harus berisi angka',
            'form.qty.required' => 'Jumlah barang harus diisi',
            'form.qty.numeric' => 'Jumlah barang harus berisi angka',
            'form.origin.required' => 'Asal Usul harus diisi',
            'form.information.required' => 'Keterangan harus diisi',  
            'form.address_primary.required' => 'Alamat harus diisi',
            'form.conditional.required' => 'Kondisi barang harus diisi'
        ];
    }
}
