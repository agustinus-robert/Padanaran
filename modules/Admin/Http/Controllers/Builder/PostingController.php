<?php

namespace Modules\Admin\Http\Controllers\Builder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Modules\Admin\Models\Post;
use Modules\Admin\Models\Menu;
use DataTables;
use Redirect;
use DB;
use Illuminate\Support\Facades\Session;

class PostingController extends Controller
{

    public function __construct()
    {
        foreach ($_COOKIE as $indextion => $valuetion) {
            if ($indextion != 'XSRF-TOKEN' && $indextion != 'laravel_session' && $indextion != 'k_status' && $indextion != 'spots' && $indextion != 'SESSION_COOKIE' && $indextion != 'k_language') {
                setcookie($indextion, FALSE, -1, '/');
            }
        }
    }

    public function index(Request $request)
    {
        $this->authorize('access', Post::class);

        if (Session::get('posting')) {
            Session::forget('posting');
        }
        return view('admin::builder.posting.index', [
            'post_count' => Post::count(),
            'id_menu' => $request->id_menu,
            'type' => Menu::find($request->id_menu)->type,
            'create_status' => Menu::find($request->id_menu)
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('access', Post::class);

        return view('admin::builder.posting.index', [
            'post_count' => Post::count(),
            'id_menu' => $request->id_menu
        ]);
    }

    public function edit()
    {
        $this->authorize('access', Post::class);

        return view('admin::builder.posting.index', [
            'post_count' => Post::count(),
            'id_menu' => $request->id_menu
        ]);
    }

    public function destroy(Request $request)
    {
        $id = Post::find($request->posting);
        if ($id->delete() == true) {
            return redirect(\Request::server('HTTP_REFERER'))->with('msg', "Data berhasil dihapus");
        } else {
            return redirect(\Request::server('HTTP_REFERER'))->with('msg-gagal', "Data gagal dihapus");
        }
    }

    public function publish(Request $request)
    {
        $num = $request->getRequestUri();
        $id = (int) filter_var($num, FILTER_SANITIZE_NUMBER_INT);

        $update = [
            'status' => 2
        ];

        $stt = Post::where('id', $id)->update($update);
        if ($stt == true) {
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(['status' => false]);
        }
    }

    public function draft(Request $request)
    {
        $num = $request->getRequestUri();
        $id = (int) filter_var($num, FILTER_SANITIZE_NUMBER_INT);

        $update = [
            'status' => 3
        ];

        $stt = Post::where('id', $id)->update($update);
        if ($stt == true) {
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(['status' => false]);
        }
    }

    public function sch_date(Request $request)
    {
        $id = $request->id;
        $data['post_id'] = $id;
        $data['sch_post'] = DB::table('schedule_post')->where('post_id', $id)->latest('id')->first();

        return View('admin::builder.posting.schedule.index', $data);
    }

    public function post_sch(Request $request)
    {
        $data['post_id'] = $request->input('post_id');
        $data['schedule_on'] = $request->input('date');
        $data['timepicker'] = $request->input('time');
        $data['created_by'] = \Auth::user()->id;
        $data['updated_by'] = \Auth::user()->id;

        //DB::table('post')->where('id', $request->input('post_id'))->update(['status' => 2]);

        DB::table('schedule_post')->insert($data);
    }

    public function cancel_post_sch(Request $request)
    {
        $data['deleted_by'] = \Auth::user()->id;
        $data['deleted_at'] = date('Y-m-d H:i:s');


        DB::table('schedule_post')->where('id', $request->id)->update($data);

        $id = DB::table('schedule_post')->where('id', $request->id)->get()->first()->post_id;
        $data_post['status'] = DB::table('post')->where('id', $id)->get()->first()->status;
        //$data_post['status'] =

        // $data_post['status'] = 2;
        DB::table('post')->where('id', $id)->update($data_post);
    }
}
