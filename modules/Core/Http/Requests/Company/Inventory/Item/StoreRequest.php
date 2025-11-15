<?php

namespace Modules\Core\Http\Requests\Company\Inventory\Item;

use Str;
use Illuminate\Validation\Rules\Enum;
use App\Http\Requests\FormRequest;
use Modules\Core\Enums\InventoryConditionEnum;
use Modules\Core\Enums\PlaceableTypeEnum;
use Modules\Core\Models\CompanyInventory;

class StoreRequest extends FormRequest
{
    public $placeable;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', CompanyInventory::class);
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
            'files.*'           => 'nullable|file|max:2048|mimes:jpg,jpeg,png,pdf',
            'count'             => 'required|numeric'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'name'              => 'nama',
            'category'          => 'kategori',
            'brand'             => 'merek',
            'condition'         => 'kondisi',
            'placeable_type'    => 'lokasi',
            'placeable_id'      => 'lokasi',
            'user_id'           => 'pengguna',
            'pic_id'            => 'penanggungjawab',
            'proposal_id'       => 'tautan',
            'quantity'          => 'pembelian',
            'bought_at'         => 'pembelian',
            'files.*'           => 'lampiran',
            'count'             => 'jumlah'
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $this->placeable_class = new (PlaceableTypeEnum::tryFrom((int) $this->input('placeable_type'))->instance())();
        $validator->sometimes('placeable_id', 'exists:'.($this->placeable_class->getTable()).',id', fn ($input) => true);
    }

    /**
     * Transform request into expected output.
     */
    public function transform ()
    {
        if($this->hasFile('files')) {
            foreach($this->file('files') as $file) {
                $files[] = [
                    'url'   => $file->store('company/inventories/item/'.$kd),
                    'name'  => $file->getClientOriginalName()
                ];
            }
        }

        for ($i = 0; $i < $this->input('count') ; $i++) { 
            $inventories[] = [
                ...$this->only('name', 'category', 'brand', 'condition', 'placeable_id', 'user_id', 'pic_id', 'proposal_id', 'quantity', 'bought_at'),
                'kd'                => time() * 1000 + $i,
                'placeable_type'    => get_class($this->placeable_class),
                // 'files.*'           => $files ?? []
            ];
        }

        return $inventories;
    }
}