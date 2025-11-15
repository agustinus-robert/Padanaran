<?php
namespace Modules\Admin\Http\Controllers\Builder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Modules\Admin\Models\PostImage;
use DataTables;
use Session;
use Redirect;
use DB;

class PostingImageController extends Controller
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
       // $this->authorize('access', Post::class);

        return view('admin::builder.posting_image.index', [
            'id_menu' => $request->id_menu,
            'post_id' => $request->post_id
        ]);
    }

    public function create(Request $request){
        //$this->authorize('access', Post::class);

        return view('admin::builder.posting_image.index', [
            'id_menu' => $request->id_menu,
            'post_id' => $request->post_id
        ]);
    }

    public function edit(){
        $this->authorize('access', Post::class);

        return view('admin::builder.posting_image.index', [
            'id_menu' => $request->id_menu,
            'post_id' => $request->post_id
        ]);
    }

    public function destroy(Request $request){
        $id=PostImage::find($request->posting_image);
        if($id->delete() == true){
            return redirect(\Request::server('HTTP_REFERER'))->with('msg', "Data berhasil dihapus");
        } else {
            return redirect(\Request::server('HTTP_REFERER'))->with('msg-gagal', "Data gagal dihapus");
        }
    }
}
