<?php

namespace Modules\Core\Http\Requests\Company\Asset\Building;

use App\Http\Requests\FormRequest;
use Modules\Core\Models\CompanyBuilding;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', CompanyBuilding::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'kd'                => 'required|max:255|string',
            'name'              => 'required|max:255|string',
            'address_primary'   => 'required|max:255|string',
            'address_secondary' => 'required|max:255|string',
            'address_city'      => 'required|max:255|string',
            'state_id'          => 'required|numeric'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'kd'                => 'kode',
            'name'              => 'nama',
            'address_primary'   => 'alamat',
            'address_secondary' => 'kecamatan',
            'address_city'      => 'kota',
            'state_id'          => 'provinsi'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return $this->validated();
    }
}
