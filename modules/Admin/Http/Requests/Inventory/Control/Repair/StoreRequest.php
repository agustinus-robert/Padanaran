<?php

namespace Modules\Asset\Http\Requests\Inventory\Control\Repair;

use Str;
use Illuminate\Validation\Rules\Enum;
use App\Http\Requests\FormRequest;
use Modules\Core\Enums\PlaceableTypeEnum;
use Modules\Core\Enums\InventoryConditionEnum;
use Modules\Core\Enums\InventoryLogActionEnum;
use Modules\Core\Models\CompanyInventory;
use Modules\Core\Models\CompanyInventoryLog;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', CompanyInventoryLog::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'item_id'        => 'required|exists:' . (new CompanyInventory())->getTable() . ',id',
            'placeable_type' => ['required', new Enum(PlaceableTypeEnum::class)],
            'placeable_id'   => 'required|numeric',
            'condition'      => ['required', new Enum(InventoryConditionEnum::class)],
            'action'         => ['required', new Enum(InventoryLogActionEnum::class)],
            'description'    => 'nullable|string|max:191',
            'content'        => 'nullable|string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'item_id'        => 'inventaris',
            'placeable_type' => 'lokasi/pengguna',
            'placeable_id'   => 'detail pengguna/lokasi',
            'condition'      => 'kondisi',
            'action'         => 'tindakan',
            'description'    => 'keterangan',
            'content'        => 'tindakan',
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        $placeable = PlaceableTypeEnum::forceTryFrom($this->placeable_type);
        $inventory = CompanyInventory::find($this->input('item_id'));

        return [
            ...$this->only('placeable_id', 'condition', 'action', 'description'),
            'inventory' => $inventory,
            'placeable_type' => $placeable->instance(),
            'name' => $inventory->name,
            'meta' => [
                'content' => $this->input('content')
            ]
        ];
    }
}
