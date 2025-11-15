<?php
namespace Modules\Admin\Http\Controllers\Builder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Account\Models\User;
use DataTables;
use Session;
use Redirect;
use DB;
use Modules\Admin\Models\Post;
use Yajra\DataTables\DataTables as Table;

class CustomsController extends Controller
{
   public function __construct(){
        foreach($_COOKIE as $indextion => $valuetion){
            if($indextion != 'XSRF-TOKEN' && $indextion != 'laravel_session' && $indextion != 'k_status' && $indextion != 'spots' && $indextion != 'SESSION_COOKIE' && $indextion != 'k_language'){
                setcookie($indextion, FALSE, -1, '/');
            }
        }
   }
   
   public function index(Request $request){
        $this->authorize('access', User::class);
        $data = [];
        // dbuilder_table untuk membuat generate table pada kolom header dan pemanggilan kolom database
        $data['column'] = [
            dbuilder_table('id', 'Id Posting', false, true),
            dbuilder_table('title', 'Judul', true, false),
            dbuilder_table('created_at', 'Dibuat Tanggal'),
            dbuilder_table('action', 'Aksi')
        ];

        return view('admin::builder.customs.index', $data);
    }

    public function getTable(Request $request){
        $post = Post::select('*');
        if ($request->has('filterTitle') && $request->filterTitle != '') {
           $post->where('content', 'like', '%' . $request->filterTitle . '%');
        }

        return Table::of($post)
            ->addColumn('id', function ($row) {
                return $row->id;
            })
            ->addColumn('title', function ($row) {
                return get_content_json($row)['id']['title'];
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at;
            })
            ->addColumn('action', function($row){
                 $template = '';
                     
                 $template .= view('admin::layouts_master.component.button_edit', array('id' => $row->id, 'update' => route('admin::builder.posting.edit', ['posting' => '?id_menu='.$row->menu_id.'&post_id='.$row->id])))->render();
                 $template .= view('admin::layouts_master.component.button_image', array('id' => $row->id, 'btnimage' => route('admin::builder.posting_image.index').'?id_menu='.$row->menu_id.'&post_id='.$row->id))->render();
                 $template .= view('admin::layouts_master.component.button_video', array('id' => $row->id, 'btnvideo' => route('admin::builder.posting_video.index').'?id_menu='.$row->menu_id.'&post_id='.$row->id))->render();
                 $template .= view('admin::layouts_master.component.button_delete', array('id' => $row->id, 'delete' => route('admin::builder.posting.destroy', ['posting' => $row->id])))->render();
                
                return $template;  
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
