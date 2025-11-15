<?php

namespace Modules\Admin\Http\Requests\Inventory\Land;

use Auth;
use App\Http\Requests\FormRequest;
use Modules\Core\Enums\PlaceableTypeEnum;
use Modules\Core\Models\Supplier;

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
            'form.latitude'    => 'required',
            'form.longitude'    => 'required',
            'form.wide' => 'required|numeric',
            'form.register_number' => 'required|numeric',
            'form.call_number' => 'required|numeric',
            'form.name' => 'required|string',
            'form.certificate_date' => 'required',
            'form.certificate_number' => 'required|numeric',
            'form.year_used' => 'required|numeric',
            'form.origin' => 'required|string|max:255',
            'form.address_primary' => 'required|string',
            'form.right_of_user' => 'required',
            'form.price' => 'required',
            'form.code_unit' => 'required',
            'form.kategori' => 'required'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'form.latitude'    => 'Latitude',
            'form.longitude'    => 'Longitude',
            'form.unit' => 'Unit',
            'form.kategori' => 'Kategori',
            'form.wide' => 'Luas',
            'form.register_number' => 'Nomor Register',
            'form.call_number' => 'Namor Telepon',
            'form.name' => 'Nama',
            'form.certificate_date' => 'Tanggal Sertifikat',
            'form.certificate_number' => 'Nomor Sertifikat',
            'form.year_used' => 'Tanggal Digunakan',
            'form.origin' => 'Pengunaan',
            'form.address_primary' => 'Alamat Utama',
            'form.right_of_user' => 'Pinjam Pakai',
            'form.price' => 'Harga',
            'form.code_unit' => 'Kode Unit',
            'form.kategori' => 'Kategori'
        ];
    }

    public function messages(): array {
         return [
            'form.latitude.required' => 'Latitude Harus diisi',
            'form.latitude.string' => 'Latitude Harus string',
            'form.longitude.required' => 'Longitude Harus diisi',
            'form.longitude.string' => 'Longitude Harus string',
            'form.unit.required' => 'Unit Harus dipilih',
            'form.kategori.required' => 'Kategori Harus dipilih',
            'form.wide.required' => 'Luas Harus diisi',
            'form.wide.numeric' => 'Luas Harus berisi angka',
            'form.register_number.required' => 'Nomor registrasi harus diisi',
            'form.register_number.numeric' => 'Nomor Registrasi harus berisi angka',
            'form.call_number.required' => 'Nomor Telepon harus diisi',
            'form.call_number.numeric' => 'Nomor Telepon harus berisi angka',
            'form.name.required' => 'Nama aset harus diisi',
            'form.name.string' => 'Nama harus berisi string',
            'form.certificate_date.required' => 'Tanggal Sertifikat harus diisi',
            'form.certificate_number.required' => 'Nomor Sertifikat harus diisi',
            'form.certificate_number.numeric' => 'Nomor sertifikat harus berisi angka',
            'form.year_used.required' => 'Tahun Pengadaan harus diisi',
            'form.year_used.numeric' => 'Tahun Pengadaan harus berisi angka',
            'form.origin.required' => 'Pengunaan harus diisi',
            'form.origin.string' => 'Pengunaan harus berisi string',
            'form.origin.max' => 'Pengunaan harus kurang dari 255 karakter',
            'form.address_primary.required' => 'Alamat utama harus diisi',
            'form.address_primary.string' => 'Alamat utama harus berisi string',
            'form.right_of_user.required' => 'Pinjam Pakai harus dipilih',
            'form.price.required' => 'Harga tidak boleh kosong',
            'form.code_unit.required' => 'Kode Unit tidak boleh Kosong',
            'form.kategori.required' => 'Kategori tidak boleh kosong'
        ];
    }
}
