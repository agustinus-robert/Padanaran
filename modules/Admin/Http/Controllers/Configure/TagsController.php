<?php
namespace Modules\Admin\Http\Controllers\Configure;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\Tags;
use DataTables;
use Session;
use Redirect;
use DB;

class TagsController extends Controller
{

   public function index(Request $request)
    {
        $this->authorize('access', Tags::class);

        return view('admin::configure.tags.index');
    }

    public function create(Request $request){
        $this->authorize('access', Tags::class);

        return view('admin::configure.tags.index');
    }

    public function edit(){
        $this->authorize('access', Tags::class);

        return view('admin::configure.tags.index');
    }

    public function destroy(Request $request){
        $id=Tags::find($request->tag);
        if($id->delete() == true){
            return redirect(\Request::server('HTTP_REFERER'))->with('msg', "Data berhasil dihapus");
        } else {
            return redirect(\Request::server('HTTP_REFERER'))->with('msg-gagal', "Data gagal dihapus");
        }

    }
 }