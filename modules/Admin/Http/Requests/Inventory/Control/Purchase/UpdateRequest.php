<?php

namespace Modules\Asset\Http\Requests\Inventory\Control\Purchase;

use Illuminate\Validation\Rules\Enum;
use App\Http\Requests\FormRequest;
use Modules\Core\Enums\PlaceableTypeEnum;

class UpdateRequest extends FormRequest
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
            'name'              => 'required|string',
            'placeable_type'    => ['required', new Enum(PlaceableTypeEnum::class)],
            'placeable_id'      => 'required|numeric',
            'bought_price'      => 'required|numeric',
            'bought_at'         => 'required',
            'description'       => 'nullable|string|max:191',
            'files'             => 'nullable',
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
            'placeable_id'   => 'detail pengguna/lokasi',
            'bought_price'   => 'harga',
            'bought_at'      => 'tanggal pembelian',
            'description'    => 'keterangan',
            'files'          => 'lampiran',
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        $placeable = PlaceableTypeEnum::forceTryFrom($this->placeable_type);

        if ($this->hasFile('files')) {
            foreach ($this->file('files') as $file) {
                $files[] = [
                    'url'   => $file->store('company/inventories/item/'),
                    'name'  => $file->getClientOriginalName()
                ];
            }
        }

        return [
            ...$this->only('name', 'placeable_id', 'bought_price', 'bought_at', 'description', 'meta'),
            'placeable_type' => $placeable->instance(),
            'files' => $files
        ];
    }
}
