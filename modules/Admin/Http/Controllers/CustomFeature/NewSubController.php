<?php
namespace Modules\Admin\Http\Controllers\CustomFeature;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\SubNews;
use DataTables;
use Session;
use Redirect;
use DB;

class NewSubController extends Controller
{

   public function index(Request $request)
    {
        $this->authorize('access', SubNews::class);
        $data['newsub'] = SubNews::count();

        return view('admin::custom_feature.news_sub.index', $data);
    }
}

?>