<?php
namespace Modules\Admin\Exception;

use Modules\Admin\Models\BuildingsLandsSell;
use Modules\Admin\Models\Land;
use Modules\admin\Models\Building;
use Illuminate\Support\Facades\Auth;
use DB;

trait BuildingSellException{
	
	public function validateStock(array $data, $type)
    {
    	if(isset($data['id_land'])){
    		$land = Land::find($data['id_land']);
        	$landStock = $land->wide - $data['sell_land'];
    	}

    	// if(isset($data['id_building'])){
        // 	$building = Building::find($data['id_building']);
        // 	//$buildingStock = $building->wide - $data['sell_building'];
		// }

        // else if($buildingStock < 0){
        //         return ['status' => false, 'stock_real_building' => $building->wide, 'message' => 'Silahkan cek stock pada bangunan'];
        //     }
    	if($type == 1){
            if($landStock < 0){
            	return ['status' => false, 'stock_real_land' => $land->wide, 'message' => 'Silahkan cek stock pada tanah'];
            }  else {
            	return ['status' => true];
            }
    	} else if($type == 2){
    		if($landStock < 0){
    			return ['status' => false, 'stock_real_land' => $land->wide, 'message' => 'Silahkan cek stock pada tanah'];
    		} else {
    			return ['status' => true];
    		}
    	} 

    	return ['status' => true];
    }
}
?>