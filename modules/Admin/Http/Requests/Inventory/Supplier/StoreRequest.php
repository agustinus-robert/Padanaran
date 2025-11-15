<?php

namespace Modules\Admin\Http\Requests\Inventory\Supplier;

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
            'form.name'    => 'required|string|max:255',
            'form.email'   => 'required|email',
            'form.phone'   => 'required|numeric',
            'form.address' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'form.name'    => 'Nama',
            'form.email'   => 'Email',
            'form.phone'   => 'Nomor Telepon',
            'form.address' => 'Alamat',
        ];
    }

    public function messages(): array {
         return [
            'form.name.required' => 'Nama harus diisi',
            'form.name.string'  => 'Nama harus berbentuk string',
            'form.name.max' => 'Panjang karakter nama maksimal sebanyak 255',
            'form.email.required' => 'Email harus diisi',
            'form.email.email'  => 'Format Email tidak sesuai',
            'form.phone.required' => 'Nomor telepon harus diisi',
            'form.phone.numeric'  => 'Nomer telepon harus berisi angka',
            'form.address.required' => 'Alamat harus diisi',
            'form.address.string'  => 'Alamat harus berbentuk string',
            'form.address.max' => 'Panjang karakter nama maksimal sebanyak 255',
        ];
    }
}
