<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Models\BuildingsLandsSell;
use Modules\Admin\Models\Land;
use Modules\Admin\Models\Room;
use Modules\admin\Models\Building;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

trait BuildingsLandsSellRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'file_sk',
        'invoice',
        'date_sell',
        'wide_land',
        'id_land',
        'remainder_land',
        'sell_land',
        'id_building',
        'type',
        'price',
        'status',
        'status_building',
        'forfeit_land',
        'early_period_land',
        'month_period_land',
        'forfeit_building',
        'early_period_building',
        'month_period_building',
        'price_lend_building'
    ];

    private $keys_update = [
        'date_sell'
    ];

    /**
     * Store newly created resource.
     */
    public function storeBuildingLands(array $data, $certificate, $type, $statuslend, $status = '')
    {
        try {
            DB::beginTransaction();
            $data['type'] = $type;
            if($status == 'lend'){
                $data['invoice'] = 'INV/PEMINJAMAN/BUILDLAND/'.random_int(100000, 999999).'/'.date('Y');
            } else {
                $data['invoice'] = 'INV/PENJUALAN/BUILDLAND/'.random_int(100000, 999999).'/'.date('Y');
            }
            

            // $data['price'] = str_replace('.','',$data['price']);
            if(!empty($certificate->getFilename())){
                $location_building_land = 'file_building_land/'.uniqid();
                $data['file_sk'] = $location_building_land.'/'.$certificate->getFilename();
                $certificate->storeAs($location_building_land, $certificate->getFilename(), 'public');
            }

            if($status == 'lend'){
                $data['status'] = 2;
            } else {
                $data['status'] = 1;
            }

            if($type == 2){
                $data['id_building'] = '';

                if($statuslend == 1){
                    $data['status_building'] = 1;
                } else {
                    $data['status_building'] = 0;
                }
            } else if($type == 3){
                $data['id_land'] = '';
                $data['wide_land'] = '';
                $data['remainder_land'] = '';
                $data['sell_land'] = '';
            }


            $buildingLands = new BuildingsLandsSell(Arr::only($data, $this->keys));
            if($buildingLands->save()){
                if($type == 1){
                    $land = Land::find($data['id_land']);
                    $data_land['wide'] = $land->wide - $data['sell_land'];
                    
                    if($land->update($data_land)){

                        $divide_land = Land::find($data['id_land'])->replicate();

                        $divide_land->wide = $data['sell_land'];
                        $divide_land->take_by = $buildingLands->id;
                        $divide_land->certificate_date = '';
                        $divide_land->certificate_number = '';
                        $divide_land->certificate_file = '';
                        $divide_land->save();
                    }

                    $building = Room::where('building_id', $data['id_building']);
                    $building->update(['is_sell' => 1]);
                    // $data_building['wide'] = $building->wide - $data['sell_building'];
                    
                    // if($building->update($data_building)){

                    //     $divide_building = Building::find($data['id_building'])->replicate();

                    //     $divide_building->wide = $data['sell_building'];
                    //     $divide_building->take_by = $buildingLands->id;
                    //     $divide_building->certificate_date = '';
                    //     $divide_building->certificate_number = '';
                    //     $divide_building->certificate_file = '';
                    //     $divide_building->save();
                    // }
                }else if($type == 2){
                    $land = Land::find($data['id_land']);
                    $data_land['wide'] = $land->wide - $data['sell_land'];
                    
                    if($land->update($data_land)){

                        $divide_land = Land::find($data['id_land'])->replicate();

                        $divide_land->wide = $data['sell_land'];
                        $divide_land->take_by = $buildingLands->id;
                        $divide_land->certificate_date = '';
                        $divide_land->certificate_number = '';
                        $divide_land->certificate_file = '';
                        $divide_land->save();
                    }
                } else if($type == 3){

                    $building = Room::where('building_id', $data['id_building']);
                    $building->update(['is_sell' => 1]);
                    // $building = Building::find($data['id_building']);
                    // $data_building['wide'] = $building->wide - $data['sell_building'];
                    
                    // if($building->update($data_building)){

                    //     $divide_building = Building::find($data['id_building'])->replicate();

                    //     $divide_building->wide = $data['sell_building'];
                    //     $divide_building->take_by = $buildingLands->id;
                    //     $divide_building->certificate_date = '';
                    //     $divide_building->certificate_number = '';
                    //     $divide_building->certificate_file = '';
                    //     $divide_building->save();
                    // }
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
    public function updateBuildingLand(array $data, $certificate, $building_land_id)
    {
        try {
            DB::beginTransaction();

            $buildingLand = BuildingsLandsSell::find($building_land_id);
            
            $data['price'] = str_replace('.','',$data['price']);
            if(!is_string($certificate) > 0){
                $location_building = 'file_building/'.uniqid();
                $data['certificate_file'] = $location_building.'/'.$certificate->getFilename();
                $certificate->storeAs($location_building, $certificate->getFilename(), 'public');
            }
            
            $buildingLand->update(Arr::only($data, $this->keys_update));
                

            
            DB::commit();
            return true;
        } catch (\PDOException $e) {
            // Woopsy
            dd($e);
            DB::rollBack();
            return false;
        }
    }

    public function backBuildingLands($id, $id_land){
        try {
            DB::beginTransaction();

            $landNow = BuildingsLandsSell::find($id);
            $land = Land::find($id_land);

            $returnLand = $land->wide + $landNow->sell_land;
            $land->wide = $returnLand;
            $land->save();

            $landNow->is_back_land = 1;
            $landNow->save();

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
    public function destroyBuildingLands($id)
    {
        $landbuild = BuildingsLandsSell::where('id', $id)->get()->first();
        
        if($landbuild->type == 1){
            $land = Land::find($landbuild->id_land);
            $returnLand = $land->wide + $landbuild->sell_land;
            $data_land['wide'] = $returnLand;
            $land->update($data_land);

            Land::where('take_by', $id)->delete();

            $building = Building::find($landbuild->id_building);
            $returnBuilding = $building->wide + $landbuild->sell_building;
            $data_building['wide'] = $returnBuilding;
            $building->update($data_building);

            Building::where('take_by', $id)->delete();
        } else if($landbuild->type == 2){
            $land = Land::find($landbuild->id_land);
            $returnLand = $land->wide + $landbuild->sell_land;
            $data_land['wide'] = $returnLand;
            $land->update($data_land);

            Land::where('take_by', $id)->delete();
        } else if($landbuild->type == 3){
            $building = Building::find($landbuild->id_building);
            $returnBuilding = $building->wide + $landbuild->sell_building;
            $data_building['wide'] = $returnBuilding;
            $building->update($data_building);

            Building::where('take_by', $id)->delete();
        }
        
        if (BuildingsLandsSell::where('id', $id)->delete()) {

            return true;
        }

        return false;
    }

    public function destroyForceBuildingLands($id)
    {
        $landbuild = BuildingsLandsSell::withTrashed()->where('id', $id)->get()->first();
        
        if($landbuild->type == 1){
            Land::withTrashed()->where('take_by', $id)->forceDelete();
            Building::withTrashed()->where('take_by', $id)->forceDelete();
        } else if($landbuild->type == 2){
            Land::withTrashed()->where('take_by', $id)->forceDelete();
        } else if($landbuild->type == 3){   
            Building::withTrashed()->where('take_by', $id)->forceDelete();
        }
        
        if (BuildingsLandsSell::withTrashed()->where('id', $id)->forceDelete()) {

            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreBuilding($id)
    {
        if (Building::onlyTrashed()->find($id)->restore()) {
            BuildingPhotos::onlyTrashed()->where('building_id', $id)->restore();
            
            return true;
        }
        return false;
    }
}
