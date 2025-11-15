<?php

namespace Modules\Asset\Http\Requests\Inventory\Proposal;

use Auth;
use App\Http\Requests\FormRequest;
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
            'name'                    => 'required|string|max:191',
            'description'             => 'required|string',
            'totalAll'                => 'required|numeric',
            'items.*.kd'              => 'required|unique:' . (new CompanyInventory())->getTable() . ',kd',
            'items.*.name'            => 'required|string|max:191',
            'items.*.quantity'        => 'required|numeric',
            'items.*.bought_price'    => 'required|numeric',
            'items.*.placeable_type'  => 'required|numeric',
            'items.*.placeable_id'    => 'required|numeric',
            'items.*.total_price'     => 'required|numeric'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'name'                   => 'nama',
            'description'            => 'kategori',
            'totalAll'               => 'total pengajuan',
            'items.*.kd'             => 'kode inventaris',
            'items.*.name'           => 'nama barang',
            'items.*.quantity'       => 'kuantitas',
            'items.*.bought_price'   => 'harga',
            'items.*.placeable_type' => 'lokasi',
            'items.*.placeable_id'   => 'pengguna',
            'items.*.total_price'    => 'total per barang'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            'name'              => $this->input('name'),
            'description'       => $this->input('description'),
            'user_id'           => Auth::user()->id,
            'items'             => ($data = $this->collect('items')->map(function ($item) {
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
