<?php

namespace Modules\Admin\Http\Requests\Inventory\Building;

use Auth;
use App\Http\Requests\FormRequest;


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
            'form.name_asset' => 'required',
            'form.land_status' => 'required',
            'form.land_condition' => 'required',
            'form.land_gradual' => 'required',
            'form.concrete' => 'required',
            'form.origin' => 'required',
            'form.kategori' => 'required',
            'form.price' => 'required',
            'form.unit' => 'required',
            // 'form.wide' => 'required|numeric',
            'form.register_number' => 'required|numeric',
            'form.name' => 'required|string',
            'form.certificate_date' => 'required',
            'form.certificate_number' => 'required|numeric',
            'form.information' => 'required',
            'form.year_document' => 'required|numeric',
            'form.address_primary' => 'required',
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
            'form.name_asset' => 'Aset',
            'form.land_status' => 'Status Tanah',
            'form.land_condition' => 'Kondisi Tanah',
            'form.land_gradual' => 'Bertingkat',
            'form.concrete' => 'Beton',
            'form.origin' => 'Asal Usul',
            'form.unit' => 'Unit',
            'form.kategori' => 'Kategori',
            'form.price' => 'Harga',
            // 'form.wide' => 'Luas',
            'form.register_number' => 'Nomor Register',
            'form.name' => 'Nama sertifikat',
            'form.certificate_date' => 'Tanggal Sertifikat',
            'form.certificate_number' => 'Nomor Sertifikat',
            'form.information' => 'Keterangan',
            'form.year_document' => 'Tahun Dokumen',
            'form.address_primary' => 'Alamat Utama',
        ];
    }

    public function messages(): array {
         return [
            'form.latitude.required' => 'Latitude harus diisi',
            'form.longitude.required' => 'Longitude harus diisi',
            'form.unit.required' => 'Unit Harus dipilih',
            'form.name_asset.required' => 'Nama aset harus diisi',
            'form.land_status.required' => 'Status Tanah harus diisi',
            'form.land_condition.required' => 'Kondisi Tanah harus diisi',
            'form.land_gradual.required' => 'Status Bangunan Bertingkat harus diisi',
            'form.concrete.required' => 'Status Beton Bangunan harus diisi',
            'form.origin.required' => 'Asal Usul harus diisi',
            'form.kategori.required' => 'Kategori Harus dipilih',
            'form.price.required' => 'Harga harus diisi',
            // 'form.price.numeric' => 'Harga harus berisi angka',
            // 'form.wide.required' => 'Luas Harus diisi',
            // 'form.wide.numeric' => 'Luas Harus berisi angka',
            'form.register_number.required' => 'Nomor registrasi harus diisi',
            'form.register_number.numeric' => 'Nomor Registrasi harus berisi angka',
            'form.name.required' => 'Nama Sertifikat harus diisi',
            'form.name.string' => 'Nama Sertifikat harus berisi string',
            'form.certificate_date.required' => 'Tanggal Sertifikat harus diisi',
            'form.certificate_number.required' => 'Nomor Sertifikat harus diisi',
            'form.certificate_number.numeric' => 'Nomor sertifikat harus berisi angka',
            'form.year_document.required' => 'Tahun Dokumen harus diisi',
            'form.year_document.numeric' => 'Tahun Dokumen harus berisi angka',
            'form.address_primary.required' => 'Alamat harus diisi',
            'form.address_primary.string' => 'Alamat harus berisi string',
            'form.information.required' => 'Keterangan harus diisi',  
        ];
    }
}
