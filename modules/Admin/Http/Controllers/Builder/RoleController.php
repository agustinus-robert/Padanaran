<?php
namespace Modules\Admin\Http\Controllers\Builder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\Role;
use DataTables;
use Session;
use Redirect;
use DB;

class RoleController extends Controller
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
        $this->authorize('access', Role::class);
        $role = Role::get();
        return view('admin::builder.role.index', ['get_role' => $role]);
    }

    public function create(Request $request){
        $this->authorize('access', Role::class);

        return view('admin::builder.role.index');
    }

    public function edit(){
        $this->authorize('access', Role::class);

        return view('admin::builder.role.index');
    }

    public function destroy(Request $request){
        $menu=Role::find($request->role);
        if($menu->delete() == true){
            return redirect(\Request::server('HTTP_REFERER'))->with('msg', "Data berhasil dihapus");
        } else {
            return redirect(\Request::server('HTTP_REFERER'))->with('msg-gagal', "Data gagal dihapus");
        }

    }
}
