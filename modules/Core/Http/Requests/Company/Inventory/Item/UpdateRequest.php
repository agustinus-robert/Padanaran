<?php

namespace Modules\Core\Http\Requests\Company\Inventory\Item;

use Str;
use Illuminate\Validation\Rules\Enum;
use App\Http\Requests\FormRequest;
use Modules\Core\Enums\InventoryConditionEnum;
use Modules\Core\Enums\PlaceableTypeEnum;
use Modules\Core\Models\CompanyInventory;

class UpdateRequest extends StoreRequest
{
    public $placeable;

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
            'name'              => 'required|string|max:191',
            'category'          => 'required|string',
            'brand'             => 'required|string',
            'condition'         => ['required', new Enum(InventoryConditionEnum::class)],
            'placeable_type'    => ['required', new Enum(PlaceableTypeEnum::class)],
            'placeable_id'      => 'required|numeric',
            'user_id'           => 'nullable|numeric',
            'pic_id'            => 'nullable|numeric',
            'proposal_id'       => 'nullable|numeric',
            'quantity'          => 'nullable',
            'bought_at'         => 'nullable',
            'files.*'           => 'nullable|file|max:2048|mimes:jpg,jpeg,png,pdf'
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $this->placeable_class = new (PlaceableTypeEnum::tryFrom((int) $this->input('placeable_type'))->instance())();
        $validator->sometimes('placeable_id', 'exists:' . ($this->placeable_class->getTable()) . ',id', fn ($input) => true);
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        if ($this->hasFile('files')) {
            foreach ($this->file('files') as $file) {
                $files[] = [
                    'url'   => $file->store('company/inventories/item/' . $kd),
                    'name'  => $file->getClientOriginalName()
                ];
            }
        }

        // 'user_id'           => $this->input('user_id'),

        $placeable = PlaceableTypeEnum::forceTryFrom($this->placeable_type);

        return [
            ...$this->only('name', 'category', 'brand', 'condition', 'placeable_id', 'pic_id', 'proposal_id', 'quantity', 'bought_at', 'bought_price'),
            'kd'                => $this->item->kd,
            'placeable_type'    => get_class($this->placeable_class),
            'user_id'           => $placeable->getUserId($this->placeable_id),
            'files.*'           => isset($files) ? (array) $files : []
        ];
    }
}
