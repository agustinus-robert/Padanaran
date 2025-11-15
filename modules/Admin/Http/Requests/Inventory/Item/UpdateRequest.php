<?php

namespace Modules\Asset\Http\Requests\Inventory\Item;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;
use Modules\Core\Enums\InventoryConditionEnum;
use Modules\Core\Enums\InventoryTypeEnum;
use Modules\Core\Enums\PlaceableTypeEnum;

class UpdateRequest extends StoreRequest
{
    public $placeable;
    public $placeable_class;

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
            'name'           => 'required|string|max:191',
            'category'       => 'required|string',
            'brand'          => 'required|string',
            'condition'      => ['required', new Enum(InventoryConditionEnum::class)],
            'placeable_type' => ['required', new Enum(PlaceableTypeEnum::class)],
            'placeable_id'   => 'required|numeric',
            'pic_id'         => 'nullable|numeric',
            'proposal_id'    => 'nullable|numeric',
            'quantity'       => 'nullable',
            'bought_price'   => 'nullable',
            'bought_at'      => 'nullable',
            'files.*'        => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf',
            'count'          => 'required|numeric',
            'sn'             => 'nullable|string',
            'description'    => 'nullable|string',
            'type'           => ['required', new Enum(InventoryTypeEnum::class)]
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
        $items = $this->item->attachments?->items ?? [];

        foreach ($items as $i => $item) {
            if (!is_null($this->input('existing_attachments'))) {
                if (!in_array($i, $this->input('existing_attachments'))) {
                    Storage::exists($item->url) ? Storage::delete($item->url) : false;
                    unset($items[$i]);
                }
            }
        }

        if ($this->hasFile('files.*')) {
            foreach ($this->file('files') as $file) {
                array_push($items, [
                    'name'  => $file->getClientOriginalName(),
                    'url'   => $file->store('company/inventories/item/user_file/'),
                ]);
            }
        }

        $placeable = PlaceableTypeEnum::forceTryFrom($this->placeable_type);

        return [
            ...$this->only('name', 'category', 'brand', 'condition', 'placeable_id', 'pic_id', 'proposal_id', 'quantity', 'bought_price', 'bought_at', 'type'),
            'kd'             => $this->item->kd,
            'placeable_type' => get_class($this->placeable_class),
            'user_id'        => $placeable->getUserId($this->placeable_id),
            'attachments'    => array_filter([
                'items'         => array_values($items)
            ]),
            'meta'           => array_filter([
                'rentable'      => $this->rentable,
                'fillable'      => $this->fillable,
                'register'      => $this->register,
                'ctg_name'      => $this->ctg_name,
                'sn'            => $this->sn,
                'inv_num'       => $this->inv_num,
                'description'   => $this->description,
                'usefull'       => $this->usefull,
            ]),
        ];
    }
}
