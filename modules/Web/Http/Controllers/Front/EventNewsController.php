<?php
namespace Modules\Web\Http\Controllers\Front;

use App\Http\Controllers\FrontEndController;
use Modules\Admin\Models\Post;
use Illuminate\Http\Request;
use Session;
use Redirect;
use DB;

class EventNewsController extends FrontEndController
{
    public function index($bahasa){
        $data = [];
        $data['lang'] = $bahasa;
        $data['caption'] = $this->get_data_by_id('64');
        $data['join_us'] = $this->get_data_by_id('69');
        $data['event_news_list'] = $this->get_data_by_id('82');

        $p_category = DB::table('post_has_category')
        ->where('tags_id', '1811731122059639')
        ->orWhere('tags_id', '1811731136561491')->get();
        $data['arr_category'] = [];
        foreach($p_category as $key => $value){
            $data['arr_category'][$value->post_id] = $value->tags_id;
        } 

        $data['program_event'] = $this->get_data_by_menu_paginate('1811038194284839', 2);
        return view('web::events.index', $data);
    }

    public function detail_event($bahasa, $slug){
        $data = [];
        $data['lang'] = $bahasa;
        $data['event_detail'] = Post::select("post.*")
        ->addSelect(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(post.content, '$.{$bahasa}.slug')) as slug"))
        ->where(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(post.content, '$.{$bahasa}.slug'))"), $slug)->first();

        $data['link_related'] = Post::select("post.*")
        ->addSelect(DB::raw("JSON_EXTRACT(post.content, '$.{$bahasa}.slug') as slug"))
        ->leftJoin('post_has_category','post_has_category.post_id','=','post.id')
        ->where(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(post.content, '$.{$bahasa}.slug'))"), '!=', $slug)
        ->where(['post_has_category.tags_id' => category_by_post_id($data['event_detail']->id)->id])
        ->where('tags_id', '1811731122059639')
        ->orWhere('tags_id', '1811731136561491')
        ->orderBy('post.id', 'desc')->limit(4)->get();

        return view('web::events.show', $data);
    }
}