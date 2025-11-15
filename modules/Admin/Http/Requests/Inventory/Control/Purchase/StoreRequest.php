<?php

namespace Modules\Asset\Http\Requests\Inventory\Control\Purchase;

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
            'items.*.bought_price' => 'required|numeric',
            'items.*.bought_at'    => 'required',
            'items.*.condition'    => ['required', new Enum(InventoryConditionEnum::class)],
            'items.*.description'  => 'nullable',
            'items.*.files'        => 'nullable',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'items.*.bought_price' => 'harga',
            'items.*.bought_at'    => 'tanggal pembelian',
            'items.*.condition'    => 'kondisi',
            'items.*.description'  => 'keterangan',
            'items.*.files'        => 'lampiran',
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform ()
    {
        return $this->input('items');
    }
}