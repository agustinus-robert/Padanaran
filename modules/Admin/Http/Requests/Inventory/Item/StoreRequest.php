<?php

namespace Modules\Asset\Http\Requests\Inventory\Item;

use Illuminate\Validation\Rules\Enum;
use App\Http\Requests\FormRequest;
use Modules\Core\Enums\InventoryConditionEnum;
use Modules\Core\Enums\InventoryTypeEnum;
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
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'name'           => 'nama',
            'category'       => 'kategori',
            'brand'          => 'merek',
            'condition'      => 'kondisi',
            'placeable_type' => 'lokasi',
            'placeable_id'   => 'lokasi',
            'pic_id'         => 'penanggungjawab',
            'proposal_id'    => 'tautan',
            'quantity'       => 'pembelian',
            'bought_price'   => 'harga',
            'bought_at'      => 'pembelian',
            'files.*'        => 'lampiran',
            'count'          => 'jumlah',
            'sn'             => 'serial number',
            'description'    => 'keterangan',
            'type'           => 'tipe inventaris'
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
        $attachments = [];

        if ($this->hasFile('files')) {
            foreach ($this->file('files') as $file) {
                $attachments[] = [
                    'name'  => $file->getClientOriginalName(),
                    'url'   => $file->store('company/inventories/item/user_file/'),
                ];
            }
        }

        list($ctg, $type, $item, $register, $year, $num) = explode("/", $this->input('inv_num'));

        for ($i = 0; $i < $this->input('count'); $i++) {
            $placeable      = PlaceableTypeEnum::forceTryFrom($this->placeable_type);
            $inventories[]  = [
                ...$this->only('name', 'category', 'brand', 'condition', 'placeable_id', 'pic_id', 'proposal_id', 'quantity', 'bought_price', 'bought_at', 'type'),
                'kd'             => time() * 1000 + $i,
                'placeable_type' => get_class($this->placeable_class),
                'user_id'        => $placeable->getUserId($this->placeable_id),
                'attachments'    => array_filter([
                    'items'         => $attachments
                ]),
                'meta'           => array_filter([
                    'rentable'      => $this->rentable,
                    'fillable'      => $this->fillable,
                    'register'      => $this->register,
                    'ctg_name'      => $this->ctg_name,
                    'sn'            => $this->sn,
                    'inv_num'       => $ctg . '/' . $type . '/' . $item . '/' . $register . '/' . $year . '/' . str_pad(($num + $i), 4, '0', STR_PAD_LEFT),
                    'description'   => $this->description
                ]),
            ];
        }

        return $inventories;
    }
}
