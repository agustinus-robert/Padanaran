<?php

namespace Modules\Asset\Http\Requests\Inventory\Proposal;

use Str;
use Illuminate\Validation\Rules\Enum;
use App\Http\Requests\FormRequest;
use Modules\Core\Enums\InventoryConditionEnum;
use Modules\Core\Enums\PlaceableTypeEnum;
use Modules\Core\Models\CompanyInventory;
use Modules\Core\Models\CompanyInventoryProposal;

class UpdateRequest extends StoreRequest
{
    public $placeable;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->proposal);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name'                  => 'required|string|max:191',
            'totalAll'              => 'required|numeric',
            'description'           => 'required|string',
            // 'items.*.kd'            => 'required|unique:' . (new CompanyInventory())->getTable() . ',kd',
            'items.*.name'          => 'required|string|max:191',
            'items.*.quantity'      => 'required|numeric',
            'items.*.bought_price'  => 'required|numeric',
            'items.*.total_price'   => 'required|numeric'
        ];
    }

    /**
     * Transform request into expected output
     * */
    public function transform()
    {
        return [
            'name'          => $this->input('name'),
            'description'   => $this->input('description'),
            'items'         => ($data = $this->collect('items')->map(function ($item) {
                $placeable = PlaceableTypeEnum::forceTryFrom($item['placeable_type']);
                return [
                    ...$item,
                    'placeable_type' => $placeable->instance(),
                    'user_id'        => $placeable->getUserId($item['placeable_id'])
                ];
            })->toArray()),
            'meta' => $data
        ];
    }
}
