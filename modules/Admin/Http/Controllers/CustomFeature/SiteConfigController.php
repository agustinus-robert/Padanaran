<?php
namespace Modules\Admin\Http\Controllers\CustomFeature;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\SiteConfig;
use DataTables;
use Session;
use Redirect;
use DB;

class SiteConfigController extends Controller
{

   public function index(Request $request)
    {
        $this->authorize('access', SiteConfig::class);
       
        return view('admin::custom_feature.site_config.index');
    }
}

?>