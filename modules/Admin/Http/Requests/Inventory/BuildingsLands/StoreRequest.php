<?php

namespace Modules\Admin\Http\Requests\Inventory\BuildingsLands;

use Auth;
use App\Http\Requests\FormRequest;


class StoreRequest extends FormRequest
{
    public $placeable;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', Supplier::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules($land, $building, $id): array
    {
        $validate = [];

        $validate['form.date_sell'] = 'required';
        
        if(empty($id)){
            if($land == 1 && $building == 1){
                $validate['form.id_land'] = 'required';
                $validate['form.sell_land'] = 'required|numeric';
                $validate['form.id_building'] = 'required';
                //$validate['form.sell_building'] = 'required|numeric';
            } else if($land == 1 && $building == 0){
                $validate['form.id_land'] = 'required';
                $validate['form.sell_land'] = 'required|numeric';
            } else if($land == 0 && $building == 1){
                $validate['form.id_building'] = 'required';
                //$validate['form.sell_building'] = 'required|numeric';
            }
        }

        
        return $validate;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'form.id_land' => 'Tanah',
            'form.date_sell' => 'Tanggal Penjualan',
            'form.sell_land' => 'Luas Tanah Dijual',
            'form.remainder_land' => 'Sisa tanah',
            'form.id_building' => 'Bangunan',
            'form.sell_building' => 'Luas Bangunan Dijual',
            'form.remainder_building' => 'Sisa bangunan'
        ];
    }

    public function messages(): array {
         return [
            'form.date_sell.required' => 'Tanggal Penjualan tidak boleh kosong',
            'form.id_land.required' => 'Tanah harus dipilih',
            'form.sell_land.required' => 'Penjualan Tanah harus diisi',
            'form.sell_land.numeric' => 'Penjualan tanah harus berisi angka',
            'form.id_building' => 'Bangunan harus dipilih',
            'form.sell_building.required' => 'Penjualan bangunan harus diisi',
            'form.sell_building.numeric' => 'Penjualan bangunan harus berisi angka'  
        ];
    }
}
