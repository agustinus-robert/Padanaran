<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Models\VehcileSell;
use Modules\Admin\Models\VehcileSellItem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

trait VehcileSellRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'invoice',
        'date_sell',
        'file_sk',
    ];

    private $item_keys = [
        'vehcile_sell_id',
        'vehcile_id',
        'vehcile_price',
        'vehcile_date'
    ];

    /**
     * Store newly created resource.
     */
    public function storeSellVehcile(array $data, $items)
    {
        try {
            DB::beginTransaction();

            $location_vehcile = 'file_vehcile/'.uniqid();

            $data['file_sk']->storeAs($location_vehcile, $data['file_sk']->getFilename(), 'public');
            $data['file_sk'] = $location_vehcile.'/'.$data['file_sk']->getFilename();
            

            $data['invoice'] = 'INV/PENJUALAN/VEH/'.random_int(100000, 999999).'/'.date('Y');
            $vehcile = new VehcileSell(Arr::only($data, $this->keys));
            
            if ($vehcile->save()){
                if(count($items) > 0){
                    foreach($items as $index => $value){
                        $save_item = [
                            'vehcile_sell_id' => $vehcile->id,
                            'vehcile_id' => $value,
                            'vehcile_price' => $data['harga'][$index]
                        ];  

                        $vehcile_sell = new VehcileSellItem(Arr::only($save_item, $this->item_keys));
                        $vehcile_sell->save();
                    }
                }
            }

            DB::commit();
            return true;

        } catch (\PDOException $e) {
            // Woopsy
            dd($e);
            DB::rollBack();
            return false;
        }
    }

    /**
     * Update the current resource.
     */
    public function updateSellVehcile(array $data, $items, $vehcile_id)
    {
        try {
            DB::beginTransaction();


            $vehcile = VehcileSell::find($vehcile_id);

            if(is_array($data['file_sk'])){
                $location_vehcile = 'file_vehcile/'.uniqid();
                $data['file_sk']->storeAs($location_vehcile, $data['file_sk']->getFilename(), 'public');
                $data['file_sk'] = $location_vehcile.'/'.$data['file_sk']->getFilename();
            }

            if ($vehcile->update(Arr::only($data, $this->keys))) {
                if(count($items) > 0){
                    VehcileSellItem::where('vehcile_sell_id', $vehcile_id)->forceDelete();
                    
                    foreach($items as $index => $value){
                        $save_item = [
                            'vehcile_sell_id' => $vehcile->id,
                            'vehcile_id' => $value,
                            'vehcile_price' => $data['harga'][$index]
                        ];  

                        $vehcile_sell = new VehcileSellItem(Arr::only($save_item, $this->item_keys));
                        $vehcile_sell->save();
                    }
                }
            }

            DB::commit();
            return true;

        } catch (\PDOException $e) {
            // Woopsy
            dd($e);
            DB::rollBack();
            return false;
        }
    }

    /**
     * Remove the current resource.
     */
    public function destroySellVehcile($id)
    {
        if (VehcileSell::where('id', $id)->delete()) {
            VehcileSellItem::where('vehcile_sell_id', $id)->delete();

            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreSellVehcile($id)
    {
        if (VehcileSell::onlyTrashed()->find($id)->restore()) {
            VehcileSellItem::onlyTrashed()->where('vehcile_sell_id', $id)->restore();

            return true;
        }
        return false;
    }
}
