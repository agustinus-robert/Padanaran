<?php

namespace Modules\Admin\Http\Requests\Inventory\Vehcile;

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
            'form.brand' => 'required',
            'form.kategori' => 'required',
            'form.unit' => 'required',
            'form.type' => 'required',
            'form.cc' => 'required',
            'form.acquisition_cost' => 'required|numeric',
            'form.material' => 'required|string',
            'form.acquisition_year' => 'required|numeric',
            // 'form.qty' => 'required|numeric',
            'form.number_chassis' => 'required',
            'form.number_machine' => 'required',
            'form.number_police' => 'required',
            'form.bpkb' => 'required',
            'form.color' => 'required',
            'form.tax_date' => 'required|date',
            'form.product_year' => 'required|numeric',
            'form.identity_number' => 'required|numeric',
            'form.assurance' => 'required',
            'form.information' => 'required',
            'form.address_primary' => 'required',
            'form.right_of_user' => 'required',
            'form.address_primary' => 'required',
            'form.information' => 'required',
            'form.pricings_lend' => 'required'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'form.brand' => 'Merek',
            'form.type' => 'Tipe',
            'form.cc' => 'CC',
            'form.acquisition_cost' => 'Harga Perolehan',
            'form.material' => 'Bahan',
            'form.acquisition_year' => 'Tahun Perolehan',
            // 'form.qty' => 'Jumlah Barang',
            'form.number_chassis' => 'Nomor Rangka',
            'form.number_machine' => 'Nomor Mesin',
            'form.number_police' => 'Nomor Polisi',
            'form.bpkb' => 'Nomor BPKB',
            'form.color' => 'Warna',
            'form.tax_date' => 'Tanggal Pajak',
            'form.product_year' => 'Tahun Produk',
            'form.identity_number' => 'Nomor Identitas',
            'form.assurance' => 'Asuransi',
            'form.information' => 'Informasi',
            'form.address_primary' => 'Aalamat',
            'form.right_of_user' => 'Pinjam Pakai',
            'form.address_primary' => 'required',
            'form.information' => 'required',
            'form.conditional' => 'required',
            'form.pricings_lend' => 'required'
        ];
    }

    public function messages(): array {
         return [
            'form.brand.required' => 'Brand harus diisi',
            'form.kategori.required' => 'Kategori Harus dipilih',
            'form.unit.required' => 'Unit Harus dipilih',
            'form.type.required' => 'Tipe Harus diisi',
            'form.cc.required' => 'CC tidak boleh kosong',
            'form.acquisition_cost.required' => 'Harga Perolehan harus diisi',
            'form.acquisition_cost.numeric' => 'Harga perolehan harus berisi angka',
            'form.material.required' => 'Bahan tidak boleh kosong',
            'form.material.string' => 'Bahan harus berisi string',
            'form.acquisition_year.required' => 'Harga perolahan harus diisi',
            'form.acquisition_year.numeric' => 'Harga perolahan harus berisi angka',
            // 'form.qty.required' => 'Jumlah barang harus diisi',
            // 'form.qty.numeric' => 'Jumlah barang harus berisi angka',
            'form.number_chassis.required' => 'Nomor rangka harus diisi',
            'form.number_machine.required' => 'Nomor mesin harus diisi',
            'form.number_police.required' => 'Nomor polisi harus diisi',
            'form.bpkb.required' => 'Nomor bpkb harus diisi',
            'form.color.required' => 'Warna harus diisi',
            'form.tax_date.required' => 'Tanggal Pajak harus dipilih',
            'form.product_year.required' => 'Tahun pajak harus diisi',
            'form.product_year.numeric' => 'Tahun pajak harus berisi angka',
            'form.identity_number.required' => 'Nomor identitas harus diisi',
            'form.identity_number.numerice' => 'Nomor identitas harus berisi angka',
            'form.assurance.required' => 'Nomor asuransi harus diisi',
            'form.information.required' => 'Informasi harus diisi',
            'form.address_primary.required' => 'Aalamat harus diisi',
            'form.right_of_user.required' => 'Pinjam Pakai harus diisi',
            'form.address_primary.required' => 'Alamat harus diisi',
            'form.information.required' => 'Informasi harus diisi',
            'form.conditional.required' => 'Kondisi harus diisi',
            'form.pricings_lend' => 'Biaya Sewa harus diisi'
        ];
    }
}
