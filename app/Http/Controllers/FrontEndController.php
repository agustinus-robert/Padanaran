<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use DB;

class FrontEndController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function output_response_json()
    {
        return response()->json($this->output_return_data, $this->output_return_code, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    protected function get_data_by_id($id)
    {
        if (env('SALES') == 1 && !empty(session()->get('selected_outlet_on'))) {
            return DB::table('post')->where(['id' => $id, 'outlet_id' => session()->get('selected_outlet_on')])->get()->first();
        } else {
            return DB::table('post')->where('id', $id)->get()->first();
        }
    }

    protected function get_data_by_menu($menu_id, $limit = '')
    {
        $post = DB::table('post')->where(['menu_id' => $menu_id, 'deleted_at' => null]);

        if (!empty($limit)) {
            $post->limit($limit); // Menggunakan variabel $limit
        }

        $post->orderBy('created_at', 'desc'); // Ubah 'created_at' dengan kolom yang sesuai

        $results = $post->get(); // Ambil hasil
        return $results;
    }

    protected function get_data_by_menu_category($menu_id, $tags_id)
    {
        return DB::table('post')
            ->select('post.*')
            ->leftJoin('post_has_category', 'post.id', '=', 'post_has_category.post_id')
            ->where('post_has_category.tags_id', $tags_id)
            ->where(['post.menu_id' => $menu_id, 'post.deleted_at' => null])->get();
    }

    protected function get_data_by_menu_paginate($menu_id, $counta = 3)
    {
        return DB::table('post')->where(['menu_id' => $menu_id, 'deleted_at' => null])->paginate($counta);
    }

    protected function get_data_by_slug($slug)
    {
        return DB::table('post')->where('slug', $slug)->get()->first();
    }

    protected function get_image_by_menu($menu_id)
    {
        return DB::table('post_image')->where('menu_id', $menu_id)->get();
    }

    protected function get_image_by_post($post_id)
    {
        return DB::table('post_image')->where('post_id', $post_id)->get();
    }

    protected function get_video_by_post($post_id)
    {
        return DB::table('post_video')->where('post_id', $post_id)->get();
    }


    protected function get_related_category_from_id($id)
    {
        $cat_id = category_by_id($id);

        return DB::table('post')->select('post.location', 'post.image')
            ->join('post_has_category as pst_h', 'pst_h.post_id', '=', 'post.id')
            ->where('pst_h.tags_id', $cat_id->tags_id)
            ->groupBy('post.id')
            ->get();
    }

    protected function get_tags_repesentation($id)
    {
        return DB::table('categoryzation as cgt')
            ->select('cgt.title', 'cgt.id')
            ->join('categoryzation_menu as cgtm', 'cgtm.id', '=', 'cgt.id_menu_category')
            ->where('cgtm.deleted_at', null)
            ->where('cgt.deleted_at', null)
            ->where('cgtm.id', $id)
            ->groupBy('cgt.id')
            ->get();
    }
}
