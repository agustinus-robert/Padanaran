<?php
namespace Modules\Admin\Http\Controllers\CustomFeature;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\CareerData;
use DataTables;
use Session;
use Redirect;
use DB;

class CareerController extends Controller
{

   public function index(Request $request)
    {
        $this->authorize('access', CareerData::class);
        $data['career'] = CareerData::count();

        return view('admin::custom_feature.career.index', $data);
    }
}

?>