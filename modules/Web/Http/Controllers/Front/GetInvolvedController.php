<?php
namespace Modules\Web\Http\Controllers\Front;

use App\Http\Controllers\FrontEndController;
use Modules\Admin\Models\Post;
use Illuminate\Http\Request;
use Session;
use Redirect;
use DB;

class GetInvolvedController extends FrontEndController
{
    public function index($bahasa){
        $data['lang'] = $bahasa;

        $data['caption'] = $this->get_data_by_id('69');
        $data['content'] = $this->get_data_by_menu('1811693073448020');

        return view('web::get-involved.index', $data);
    }

    public function donation($invoice = null)
    {
        $data = [];
        if(!empty($invoice)){
            $data['invoice'] = $invoice;
        }
        return view('web::get-involved.donation', $data);
    }

    public function volunteer()
    {
        return view('web::get-involved.volunteer');
    }

    public function partnership(){
        return view('web::get-involved.partnering');
    }

    public function career($bahasa){
        $data = [];
        $data['lang'] = $bahasa;

        $data['career_content'] = $this->get_data_by_menu('1812307928245588');
        return view('web::get-involved.career.index', $data);
    }

    public function detailedCareer($bahasa, $slug){
        $data = [];

        $data['lang'] = $bahasa;
        $data['career_detail'] = Post::select("post.*")
        ->addSelect(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(post.content, '$.{$bahasa}.slug')) as slug"))
        ->where(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(post.content, '$.{$bahasa}.slug'))"), $slug)
        ->where('post.deleted_at',null)->first();

        $data['career_related'] = Post::select("post.*")
        ->addSelect(DB::raw("JSON_EXTRACT(post.content, '$.{$bahasa}.slug') as slug"))
        ->where(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(post.content, '$.{$bahasa}.slug'))"), '!=', $slug)
        ->where('post.deleted_at',null)->get();

        return view('web::get-involved.career.show', $data);
    }
}