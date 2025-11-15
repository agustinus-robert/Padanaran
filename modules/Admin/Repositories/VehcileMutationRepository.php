<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Models\MutationVehcile;
use Modules\Admin\Models\Vehcile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

trait VehcileMutationRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'date_sk',
        'file_sk',
        'number_sk',
        'date_mutation',
        'vehcile_id',
        'unit_from',
        'unit_to',
        'information',
        'vehcile_new_id'
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
    public function storeMutationVehcile(array $data)
    {
        try {
            DB::beginTransaction();
            $mutateFromVehcileId = Vehcile::find($data['vehcile_id'])->replicate();
            $mutateFromVehcileId->code_unit = $data['unit_to'];
            
            if($mutateFromVehcileId->save()){
                $vehcileUpdate = Vehcile::find($data['vehcile_id']);
      
                $vehcileUpdate->id = $data['vehcile_id']; //already exists in database.
                $vehcileUpdate->moved_status = 1;
                $vehcileUpdate->moved_to = $data['unit_to'];
                $vehcileUpdate->moved_from = $data['unit_from'];
                $vehcileUpdate->save();
            }
            
            
            $location_mutation_vehcile = 'file_mutation_vehcile/'.uniqid();

            $data['file_sk']->storeAs($location_mutation_vehcile, $data['file_sk']->getFilename(), 'public');
            $data['file_sk'] = $location_mutation_vehcile.'/'.$data['file_sk']->getFilename();
            $data['vehcile_new_id'] = $mutateFromVehcileId->id;
            $vehcile_mutation = new MutationVehcile(Arr::only($data, $this->keys));
            
            $vehcile_mutation->save();

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
    public function updateMutationVehcile(array $data, $mutationVehcileId)
    {
        try {
            DB::beginTransaction();


            $vehcileMutation = MutationVehcile::find($mutationVehcileId);

            if(is_array($data['file_sk'])){
                $location_mutation_vehcile = 'file_mutation_vehcile/'.uniqid();
                $data['file_sk']->storeAs($location_mutation_vehcile, $data['file_sk']->getFilename(), 'public');
                $data['file_sk'] = $location_mutation_vehcile.'/'.$data['file_sk']->getFilename();
            }

            $vehcileMutation->update(Arr::only($data, $this->edit_keys));

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
