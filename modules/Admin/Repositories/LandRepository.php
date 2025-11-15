<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Models\Land;
use Modules\Admin\Models\LandPhotos;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

trait LandRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'unit_id',
        'code',
        'code_unit',
        'code_sub',
        'code_goods',
        'wide',
        'register_number',
        'call_number',
        'classification',
        'certificate_date',
        'certificate_number',
        'certificate_file',
        'name',
        'year_used',
        'right_of_user',
        'used_for',
        'origin',
        'json_maps',
        'address_primary',
        'address_secondary',
        'address_city',
        'state_id',
        'price'
    ];

    private $keys_photo = [
         'land_id',
         'location',
         'filename'
    ];

    /**
     * Store newly created resource.
     */
    public function storeLand(array $data, array $photo, $certificate)
    {
        try {
            DB::beginTransaction();

            $location_land = 'file_land/'.uniqid();
            $data['json_maps'] = json_encode([
                'lat' => $data['latitude'], 'lng' => $data['longitude']
            ]);


            $data['certificate_file'] = $location_land.'/'.$certificate->getFilename();
            $data['price'] = str_replace('.','',$data['price']);
            $certificate->storeAs($location_land, $certificate->getFilename(), 'public');

            $land = new Land(Arr::only($data, $this->keys));
            

            if ($land->save()) {
                if(count($photo) > 0){
                    foreach($photo as $index_photos => $value_photos){
                        
                        $location = 'image_land/'.uniqid();
                        $arr_photo = [
                            'land_id' => $land->id,
                            'location' => $location,
                            'filename' => $value_photos->getFilename()
                        ];

                        $value_photos->storeAs($location, $value_photos->getFilename(), 'public');

                        $land_photo = new LandPhotos(Arr::only($arr_photo, $this->keys_photo));
                        $land_photo->save();
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
    public function updateLand(array $data, array $photo, $certificate, $land_id)
    {
        try {
            DB::beginTransaction();

            $land = Land::find($land_id);
            
            $data['price'] = str_replace('.','',$data['price']);
            $data['json_maps'] = json_encode([
                'lat' => $data['latitude'], 'lng' => $data['longitude']
            ]);

            if(!empty($certificate) > 0){
                $location_land = 'file_land/'.uniqid();
                $data['certificate_file'] = $location_land.'/'.$certificate->getFilename();
                $certificate->storeAs($location_land, $certificate->getFilename(), 'public');
            }
            // $land = new Land(Arr::only($data, $this->keys));
            
            if ($land->update(Arr::only($data, $this->keys))) {
                if(count($photo) > 0){
                    LandPhotos::where('land_id', $land_id)->forceDelete();

                    foreach($photo as $index_photos => $value_photos){
                        
                        $location = 'image_land/'.uniqid();
                        $arr_photo = [
                            'land_id' => $land->id,
                            'location' => $location,
                            'filename' => $value_photos->getFilename()
                        ];

                        $value_photos->storeAs($location, $value_photos->getFilename(), 'public');

                        //$land_photo = new LandPhotos(Arr::only($arr_photo, $this->keys_photo));
                        $land_photo = new LandPhotos(Arr::only($arr_photo, $this->keys_photo));
                        $land_photo->save();
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
    public function destroyLand($id)
    {
        if (Land::where('id', $id)->delete()) {
            LandPhotos::where('land_id', $id)->delete();

            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreLand($id)
    {
        if (Land::onlyTrashed()->find($id)->restore()) {
            LandPhotos::onlyTrashed()->where('land_id', $id)->restore();
            
            return true;
        }
        return false;
    }
}
