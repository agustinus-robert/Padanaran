<?php
namespace Modules\Admin\Http\Controllers\Builder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\OrderMenu;
use DataTables;
use Session;
use Redirect;
use DB;

class OrderController extends Controller
{
   public function __construct(){
        foreach($_COOKIE as $indextion => $valuetion){
            if($indextion != 'XSRF-TOKEN' && $indextion != 'laravel_session' && $indextion != 'k_status' && $indextion != 'spots' && $indextion != 'SESSION_COOKIE' && $indextion != 'k_language'){
                setcookie($indextion, FALSE, -1, '/');
            }
        }
   }
   
   public function index(Request $request)
    {
        $this->authorize('access', OrderMenu::class);

        return view('admin::builder.order.index');
    }
}
