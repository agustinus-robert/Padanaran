<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Models\Building;
use Modules\Admin\Models\BuildingPhotos;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

trait BuildingRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'unit_id',
        'name_asset',
        'land_status',
        'land_condition',
        'land_gradual',
        'concrete',
        'code',
        'code_unit',
        'code_sub',
        'code_goods',
        // 'wide',
        'price',
        'register_number',
        'certificate_date',
        'certificate_number',
        'certificate_file',
        'name',
        'year_document',
        'information',
        'origin',
        'json_maps',
        'address_primary',
        'address_secondary',
        'address_city',
        'state_id'
    ];

    private $keys_photo = [
         'building_id',
         'location',
         'filename'
    ];

    /**
     * Store newly created resource.
     */
    public function storeBuilding(array $data, array $photo, $certificate)
    {
        try {
            DB::beginTransaction();

            $location_building = 'file_building/'.uniqid();
            $data['json_maps'] = json_encode([
                'lat' => $data['latitude'], 'lng' => $data['longitude']
            ]);


            $data['certificate_file'] = $location_building.'/'.$certificate->getFilename();
            $data['price'] = str_replace('.','',$data['price']);
            $certificate->storeAs($location_building, $certificate->getFilename(), 'public');

            $building = new Building(Arr::only($data, $this->keys));
            

            if ($building->save()) {
                if(count($photo) > 0){
                    foreach($photo as $index_photos => $value_photos){
                        
                        $location = 'image_building/'.uniqid();
                        $arr_photo = [
                            'building_id' => $building->id,
                            'location' => $location,
                            'filename' => $value_photos->getFilename()
                        ];

                        $value_photos->storeAs($location, $value_photos->getFilename(), 'public');

                        $building_photo = new BuildingPhotos(Arr::only($arr_photo, $this->keys_photo));
                        $building_photo->save();
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
    public function updateBuilding(array $data, array $photo, $certificate, $building_id)
    {
        try {
            DB::beginTransaction();

            $building = Building::find($building_id);
            

            $data['json_maps'] = json_encode([
                'lat' => $data['latitude'], 'lng' => $data['longitude']
            ]);

            $data['price'] = str_replace('.','',$data['price']);

            if(!empty($certificate) > 0){
                $location_building = 'file_building/'.uniqid();
                $data['certificate_file'] = $location_building.'/'.$certificate->getFilename();
                $certificate->storeAs($location_building, $certificate->getFilename(), 'public');
            }
            
            if ($building->update(Arr::only($data, $this->keys))) {
                if(count($photo) > 0){
                    BuildingPhotos::where('building_id', $building_id)->forceDelete();

                    foreach($photo as $index_photos => $value_photos){
                        
                        $location = 'image_building/'.uniqid();
                        $arr_photo = [
                            'building_id' => $building->id,
                            'location' => $location,
                            'filename' => $value_photos->getFilename()
                        ];

                        $value_photos->storeAs($location, $value_photos->getFilename(), 'public');
                        $building_photo = new BuildingPhotos(Arr::only($arr_photo, $this->keys_photo));
                        $building_photo->save();
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
    public function destroyBuilding($id)
    {
        if (Building::where('id', $id)->delete()) {
            BuildingPhotos::where('building_id', $id)->delete();

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
