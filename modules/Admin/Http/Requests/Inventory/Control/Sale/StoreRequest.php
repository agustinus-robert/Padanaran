<?php

namespace Modules\Asset\Http\Requests\Inventory\Control\Sale;

use App\Http\Requests\FormRequest;
use Modules\Core\Enums\InventoryLogActionEnum;
use Modules\Core\Models\CompanyInventory;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name'        => 'required|string',
            'total'       => 'required|numeric',
            'items'       => 'nullable',
            'description' => 'nullable',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'name'        => 'judul',
            'total'       => 'subtotal',
            'items'       => 'detail',
            'description' => 'keterangan',
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        foreach ($this->input('items') as $v) {
            $data[] = array_merge([
                'inv_name' => CompanyInventory::find($v['inventory_id'])->name,
                'inv_num' => CompanyInventory::find($v['inventory_id'])->meta->inv_num,
            ], $v);
        }

        $this->merge([
            'items' => array_values($data)
        ]);

        $type = InventoryLogActionEnum::SELL->value;

        return [
            ...$this->only('name'),
            'type' => $type,
            'meta' => $this->only('items', 'total', 'description'),
        ];
    }
}
