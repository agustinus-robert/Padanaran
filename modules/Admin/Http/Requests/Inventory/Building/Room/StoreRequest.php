<?php

namespace Modules\Admin\Http\Requests\Inventory\Building\Room;

use Auth;
use App\Http\Requests\FormRequest;
use Modules\Admin\Models\Floor;


class StoreRequest extends FormRequest
{
    public $placeable;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', Floor::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'form.name' => 'required',
            'form.price' => 'required',
            'form.building_id'    => 'required',
            'form.floor_id' => 'required',
            'form.information' => 'required'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'form.name' => 'Ruangan',
            'form.price' => 'Harga',
            'form.building_id'    => 'Bangunan',
            'form.floor_id' => 'Lantai',
            'form.information' => 'Informasi'
        ];
    }

    public function messages(): array {
         return [
            'form.name.required' => 'Ruangan harus diisi',
            'form.price.required' => 'Harga harus diisi',
            'form.building_id.required' => 'Bangunan harus dipilih',
            'form.floor_id' => 'Lantai harus dipilih',
            'form.information.required' => 'Informasi harus diisi'
        ];
    }
}
