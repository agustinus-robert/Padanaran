<?php
namespace Modules\Admin\Exception;

use Modules\admin\Models\Vehcile;
use Illuminate\Support\Facades\Auth;
use DB;

trait MutationVehcileException{
	
	public function validateMutationVehcile(array $data){
		if(Vehcile::find($data['vehcile_id'])->moved_status == true){
			return ['status' => false, 'vehcile_id' => $data['vehcile_id'], 'message' => 'Kendaraan sudah dipindahkan'];
		} else {
			return ['status' => true];
		}
	}
}