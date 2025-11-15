<?php
namespace Modules\Web\Http\Controllers\Front;

use App\Http\Controllers\FrontEndController;
use Illuminate\Http\Request;
use Modules\Admin\Models\Post;
use Session;
use Redirect;
use DB;

class ProgramController extends FrontEndController
{
    public function index($bahasa){
        $data = [];
        $data['lang'] = $bahasa;
        $data['content_call_us'] = $this->get_data_by_id('52');
        $data['join_us'] = $this->get_data_by_id('69');
        $data['caption_program'] = $this->get_data_by_id('58');
        $data['caption_area_program'] = $this->get_data_by_id('59');

        $p_category = DB::table('post_has_category')
        ->where('tags_id', '1811731122059639')->get();
        $data['arr_category'] = [];
        foreach($p_category as $key => $value){
            $data['arr_category'][$value->post_id] = $value->tags_id;
        } 

        $data['program_event'] = $this->get_data_by_menu_paginate('1811038194284839', 3);
        $data['content_area_program'] = $this->get_data_by_menu('1811686123553390');
        $data['upcoming_event'] = $this->get_data_by_id('21');
        $data['upcoming_event_konten'] = $this->get_data_by_menu('1811038194284839');

        return view('web::works.index', $data);
    }

    public function detail_program($bahasa, $slug){
        $data = [];
        $data['lang'] = $bahasa;
        $data['program_detail'] = Post::select("post.*")
        ->addSelect(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(post.content, '$.{$bahasa}.slug')) as slug"))
        ->where(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(post.content, '$.{$bahasa}.slug'))"), $slug)->first();

        $data['link_related'] = Post::select("post.*")
        ->addSelect(DB::raw("JSON_EXTRACT(post.content, '$.{$bahasa}.slug') as slug"))
        ->leftJoin('post_has_category','post_has_category.post_id','=','post.id')
        ->where(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(post.content, '$.{$bahasa}.slug'))"), '!=', $slug)
        ->where(['post.menu_id' => 1811038194284839, 'post_has_category.tags_id' => category_by_post_id($data['program_detail']->id)->id])
        ->orderBy('post.id', 'desc')->limit(4)->get();

        return view('web::works.show', $data);
    }
}