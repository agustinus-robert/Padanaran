<?php
namespace Modules\Admin\Http\Controllers\CustomFeature;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\Partnership;
use DataTables;
use Session;
use Redirect;
use DB;

class PartnershipController extends Controller
{

   public function index(Request $request)
    {
        $this->authorize('access', Partnership::class);
       
        return view('admin::custom_feature.partnership.index');
    }

    public function approve($partnership_id, $status){
      $cekPartnership = Partnership::find($partnership_id);

      $cekPartnership->status = $status;
      if($cekPartnership->save()){
         return redirect(\Request::server('HTTP_REFERER'))->with('msg', "Status Sudah Diubah"); 
      }

      return redirect(\Request::server('HTTP_REFERER'))->with('msg-server', "Status Gagal Diubah");
    //  return redirect(\Request::server('HTTP_REFERER'))->with('msg', "Status Sudah Diubah");
    }
}

?>