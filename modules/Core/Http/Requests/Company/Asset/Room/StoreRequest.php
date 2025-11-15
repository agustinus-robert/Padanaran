<?php

namespace Modules\Core\Http\Requests\Company\Asset\Room;

use App\Http\Requests\FormRequest;
use Modules\Core\Models\CompanyBuilding;
use Modules\Core\Models\CompanyBuildingRoom;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', CompanyBuildingRoom::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'kd'            => 'required|unique:' . (new CompanyBuildingRoom)->getTable() . ',kd|string|max:191',
            'building_id'   => 'required|numeric|exists:' . (new CompanyBuilding)->getTable() . ',id',
            'name'          => 'required|max:191|string',
            'capacity'      => 'nullable|numeric',
            'length'        => 'nullable|numeric',
            'width'         => 'nullable|numeric',
            'rentable'      => 'nullable|numeric',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'kd'            => 'kode',
            'building_id'   => 'gedung',
            'name'          => 'nama ruang',
            'capacity'      => 'kapasitas ruang',
            'length'        => 'panjang ruang',
            'width'         => 'lebar ruang',
            'rentable'      => 'jenis ruang',
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            'kd'            => $this->input('kd'),
            'building_id'   => $this->input('building_id'),
            'name'          => $this->input('name'),
            'meta'          => array_filter([
                'capacity'      => $this->input('capacity'),
                'lenght'        => $this->input('lenght'),
                'width'         => $this->input('width'),
                'rentable'      => $this->input('rentable'),
            ])
        ];
    }
}
