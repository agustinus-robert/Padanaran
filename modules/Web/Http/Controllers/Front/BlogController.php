<?php
namespace Modules\Web\Http\Controllers\Front;

use App\Http\Controllers\FrontEndController;
use Illuminate\Http\Request;
use Modules\Admin\Models\Post;
use Artisan;
use Session;
use Redirect;
use DB;

class BlogController extends FrontEndController
{
    public function index($bahasa){

        $data = [];
        $data['lang'] = $bahasa;
        $data['blog_content'] = $this->get_data_by_menu('1811605897030811');
        $data['kategori'] = DB::table('categoryzation')->where(['id_menu_category' => 1811605665005864, 'deleted_at' => null])->get();
        $data['blog_last_content'] = DB::table('post')->where('menu_id', 1811605897030811)->orderBy('id', 'desc')->limit(1)->first();

        return view('web::blogs.index', $data);
    }

    public function detail_blog($bahasa, $slug){
        $data = [];
        $data['lang'] = $bahasa;
        $data['blog_detail'] = Post::select("post.*")
        ->addSelect(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(post.content, '$.{$bahasa}.slug')) as slug"))
        ->where(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(post.content, '$.{$bahasa}.slug'))"), $slug)->first();

        $data['link_related'] = Post::select("post.*")
        ->addSelect(DB::raw("JSON_EXTRACT(post.content, '$.{$bahasa}.slug') as slug"))
        ->leftJoin('post_has_category','post_has_category.post_id','=','post.id')
        ->where(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(post.content, '$.{$bahasa}.slug'))"), '!=', $slug)
        ->where(['post.menu_id' => 1811605897030811, 'post_has_category.tags_id' => category_by_post_id($data['blog_detail']->id)->id])
        ->orderBy('post.id', 'desc')->limit(4)->get();

        return view('web::blogs.show', $data);
    }
}