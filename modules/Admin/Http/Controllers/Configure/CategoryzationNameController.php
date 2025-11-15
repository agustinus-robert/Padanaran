<?php
namespace Modules\Admin\Http\Controllers\Configure;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\CategoryzationMenu;
use DataTables;
use Session;
use Redirect;
use DB;

class CategoryzationNameController extends Controller
{

   public function index(Request $request)
    {
        $this->authorize('access', CategoryzationMenu::class);

        return view('admin::configure.categoryzation_name.index');
    }

    public function create(Request $request){
        $this->authorize('access', CategoryzationMenu::class);

        return view('admin::configure.categoryzation_name.index');
    }

    public function edit(){
        $this->authorize('access', CategoryzationMenu::class);

        return view('admin::configure.categoryzation_name.index');
    }

    public function destroy(Request $request){
        $id=CategoryzationMenu::find($request->categoryzation_name);
        if($id->delete() == true){
            return redirect(\Request::server('HTTP_REFERER'))->with('msg', "Data berhasil dihapus");
        } else {
            return redirect(\Request::server('HTTP_REFERER'))->with('msg-gagal', "Data gagal dihapus");
        }

    }
 }