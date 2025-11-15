<?php

namespace Modules\Asset\Http\Requests\Inventory\Control\Restock;

use Str;
use Illuminate\Validation\Rules\Enum;
use App\Http\Requests\FormRequest;
use Modules\Core\Enums\PlaceableTypeEnum;
use Modules\Core\Enums\InventoryLogActionEnum;
use Modules\Core\Models\CompanyInventory;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->item);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name'           => 'required|string',
            'placeable_type' => ['required', new Enum(PlaceableTypeEnum::class)],
            'action'         => ['required', new Enum(InventoryLogActionEnum::class)],
            'placeable_id'   => 'required|numeric',  
            'quantity'       => 'required|numeric',         
            'description'    => 'nullable|string|max:191',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'name'           => 'name',
            'placeable_type' => 'lokasi/pengguna',
            'action'         => 'kondisi',
            'placeable_id'   => 'detail pengguna/lokasi',
            'quantity'       => 'jumlah',
            'description'    => 'keterangan',
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform ()
    {
        $placeable = PlaceableTypeEnum::forceTryFrom($this->placeable_type);
        
        return [
            ...$this->only('name', 'placeable_id', 'description', 'quantity', 'action'),
                'placeable_type' => $placeable->instance(),
        ];
    }
}