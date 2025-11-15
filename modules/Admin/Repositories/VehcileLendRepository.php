<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Models\Vehcile;
use Modules\Admin\Models\VehcileLend;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

trait VehcileLendRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'vehcile_id',
        'file_sk',
        'end_date',
        'start_date',
        'forheit_price'
    ];

    private $edit_keys = [
        'date_sk',
        'file_sk',
        'number_sk',
        'date_mutation',
        'information'
    ];

    /**
     * Store newly created resource.
     */
    public function storeLendVehcile(array $data)
    {
        try {
            DB::beginTransaction();
            $vehcileUpdate = Vehcile::find($data['vehcile_id']);
      
            $vehcileUpdate->id = $data['vehcile_id']; //already exists in database.
            $vehcileUpdate->is_lend = 1;
            $vehcileUpdate->save();

            $location_lend_vehcile = 'file_lend_vehcile/'.uniqid();
            $data['file_sk']->storeAs($location_lend_vehcile, $data['file_sk']->getFilename(), 'public');
            $data['file_sk'] = $location_lend_vehcile.'/'.$data['file_sk']->getFilename();
            
            $vehcileLend = new VehcileLend(Arr::only($data, $this->keys));
            $vehcileLend->save();

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
    public function saveVehcileForheitData(array $data)
    {
        try {
            DB::beginTransaction();


            $vehcileMutationForheit = VehcileLend::find($data['id']);
            $vehcileMutationForheit->id = $data['id'];
            $vehcileMutationForheit->forheit_slice = $data['forheit_slice'];
            $vehcileMutationForheit->save();

            DB::commit();
            return true;

        } catch (\PDOException $e) {
            // Woopsy
            dd($e);
            DB::rollBack();
            return false;
        }
    }

    public function restoreLendVehcile($id){
        try {
            DB::beginTransaction();

            $vehcileBack = VehcileLend::find($id);

            $vehcileRestoring = Vehcile::find($vehcileBack->vehcile_id);
            $vehcileRestoring->is_lend = null;
            $vehcileRestoring->save();

            DB::commit();
            return true;

        } catch (\PDOException $e) {
            // Woopsy
            dd($e);
            DB::rollBack();
            return false;
        }
    }

    public function returMutationVehcile($id){
         try {
            DB::beginTransaction();

            $mutateCurrentVehcile = MutationVehcile::find($id);
            $mutateCurrentVehcile->id = $id; //already exists in database.
            $mutateCurrentVehcile->is_return = 1;
            $mutateCurrentVehcile->save();

            $mutateFromVehcileId = Vehcile::find($mutateCurrentVehcile['vehcile_new_id'])->replicate();
            $mutateFromVehcileId->code_unit = $mutateCurrentVehcile->unit_from;

            if($mutateFromVehcileId->save()){
                $vehcileUpdate = Vehcile::find($mutateCurrentVehcile['vehcile_new_id']);
          
                $vehcileUpdate->id = $mutateCurrentVehcile['vehcile_new_id']; //already exists in database.
                $vehcileUpdate->moved_status = 1;
                $vehcileUpdate->moved_to = $mutateCurrentVehcile->unit_from;
                $vehcileUpdate->moved_from = $mutateCurrentVehcile->unit_to;
                $vehcileUpdate->save();
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
