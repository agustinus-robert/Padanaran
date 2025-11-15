<?php

namespace App\Livewire\Log;

use Livewire\Component;
use DB;

class Userlog extends Component
{
    public $role;
    public $source_tags = [];
    public $total_post = 0;
    public $published_post = 0;
    public $draft_post = 0;
    public $deleted_post = 0;
    public $label = [];
    public $mdata = ['labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        'datasets' => [
            [
                'label' => 'Sales',
                'backgroundColor' => '#f87979',
                'data' => [12, 19, 3, 5, 2, 3, 11],
            ]
        ]
    ];
    public $refresh = [];
    public $user_value = '';

    public $valuew = [];
    public function mount(){
        $user = \Auth::user()->id;
 
        $this->role = DB::table("users")->where('id', $user)->get()->first()->role_id;
        $k_role = $this->role;

        if($k_role == 1){

        }
    }

    public function changeUserStatistic($id_user){
        $this->user_value = DB::table("users")->where('id', $id_user)->get()->first()->name;
        $labels = DB::table("categoryzation")->select('title','id')->orderBy('id')->get()->toArray();
        $tags = DB::table("tags")->select('title')->orderBy('id')->get()->toArray();
        $arr_label = [];
        $arr_color_category = [];
        foreach($labels as $klabel => $vlabel){
            $arr_label[] = $vlabel->title;
            $r = rand(0, 255);
            $g = rand(0, 255);
            $b = rand(0, 255);
            $color = dechex($r).dechex($g).dechex($b);
            $arr_color_category[] = 'rgb('.$r.','.$g.','.$b.')';
            
        }


        $arr_tags = [];
        $arr_color_tags = []; 
        foreach($tags as $ktg => $vtg){
            $arr_tags[] = $vtg->title;
            $r = rand(0, 255);
            $g = rand(0, 255);
            $b = rand(0, 255);
            $color = dechex($r).dechex($g).dechex($b);
            $arr_color_tags[] = 'rgb('.$r.','.$g.','.$b.')';
            $this->source_tags[$vtg->title] = 'rgb('.$r.','.$g.','.$b.')';
        }


      
        $this->total_post = DB::table("post")->where(["created_by" => $id_user, 'deleted_at' => null])->get()->count();
        $this->published_post = DB::table("post")->where(["created_by" => $id_user, "status" => 1])->count();
        $this->draft_post = DB::table("post")->where(["created_by" => $id_user, "status" => 2])->count();
        $this->deleted_post = DB::table("post")->where("created_by", $id_user)->where('deleted_at', '<>', null)->count();
        $tagsz = DB::table("post")->select("tags")->where(["created_by" => $id_user, 'deleted_at' => null])->get()->toArray();

        $join_query_user = DB::table("post")
        ->select("tags_id")
        ->join("post_has_category", "post_has_category.post_id", "=", "post.id")
        ->where(["post.created_by" => $id_user, 'post.deleted_at' => null])
        ->get()->toArray();

        $line_post = DB::table("post")->where(["created_by" => $id_user, 'deleted_at' => null])->get()->toArray();

        $num_post = [];
        foreach($line_post as $kpost => $vpost){
            @$num_post[date('m', strtotime($vpost->created_at))] += 1;
        }

        $ttl_post = [];
        foreach($num_post as $kpp => $vpp){
            $ttl_post[] = $vpp;
        }



        $arr_value_label = [];
        foreach($labels as $index_category => $value_category){
            foreach($join_query_user as $index_join => $value_join){
                if($value_category->id == $value_join->tags_id){
                    @$arr_value_label[$value_category->id] += 1;
                }
            }
        }

        $arr_value_tags = [];
        foreach($tags as $index_tags => $value_tags){
            foreach($tagsz as $index_tagsz => $value_tagsz){
                if(strpos(strtolower($value_tagsz->tags), strtolower($value_tags->title)) !== false){
                    @$arr_value_tags[$value_tags->title] += 1;
                }
            }
        }

        $arr_value_category = [];
        foreach($arr_value_label as $index_post_category => $value_post_category){
            $arr_value_category[] = $value_post_category; 
        }

        $arr_value_tags_dt = [];
        foreach($arr_value_tags as $index_tagsx => $value_tagsx){
            $arr_value_tags_dt[] = $value_tagsx;
        }


  
        $dts = ['labels' => $arr_label,
            'datasets' => [
                [
                    'label' => 'Sls',
                    'backgroundColor' => $arr_color_category,
                    'data' => $arr_value_category,
                ]
            ]
        ];

        $dtg = ['labels' => $arr_tags,
            'datasets' => [
                [
                    'label' => 'Sls',
                    'backgroundColor' => $arr_color_tags,
                    'data' => $arr_value_tags_dt,
                ]
            ]
        ]; 


        $years = ['labels' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'november', 'Desember'],
            'datasets' => [
                [
                    'data' => $ttl_post,
                ]
            ]
        ]; 
        

        $this->dispatch('refresh_chart_category', data_chart : $dts);
        $this->dispatch('refresh_chart_tags', circle_chart: $dtg);
        $this->dispatch('refresh_line_chart', line_chart: $years);
    }

    public function render()
    {
        if(Session::get('tema') == 'bootstrap'){
            return view('livewire.log.userlog')
        ->extends('components.layouts.app')
        ->section('konten');
        } else {
            return view('livewire.log.userlog')
        ->extends('components.layouts.app_t2')
        ->section('konten');
        }
    }
}
