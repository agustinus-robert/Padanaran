<?php

namespace App\Livewire\Builder;

use Livewire\Component;
use App\Events\MenuCreated;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Http\Request;
use Cookie;
use DB;

class Wizard extends Component
{

    // protected $listeners = [
    //     'delete_row_site',
    // ];


    public $cur_step = 1;
    public $title = [];
    public $icon;
    public $type;
    public $slug = [];
    public $meta_menu = [];
    public $category_menu = '';
    public $tags_menu = '';

    public $counts_sites = 1;

    public $conf_site = [
    ];

    public $counts_taxo = 1;

    public $conf_taxo = [
    ];

    public $size_desktop = '';
    public $size_mobile = '';
    public $menu_category;
    public $is_there_menu = [];
    public $data_id = '';
    public $custom_menu = '';
    public $language = 'id';

    public function mount($id, Request $req){
        if(!empty($id) && is_string($id)){
            $this->data_id = $id;

            $data_to_cookie = DB::table('menu')->where('id', $id)->first();

            $this->icon = $data_to_cookie->icon;

            if(isset($_COOKIE['_x_title'])){
                $this->title[$this->language] = json_decode($data_to_cookie->title, true)[$this->language];
            } else {
                $this->title = json_decode($data_to_cookie->title, JSON_UNESCAPED_SLASHES);
            }


            if(isset($_COOKIE['_x_slug'])){
                $this->slug[$this->language] = json_decode($data_to_cookie->slug, true)[$this->language];
            } else {
                $this->slug = json_decode($data_to_cookie->slug, JSON_UNESCAPED_SLASHES);
            }

            if(isset($_COOKIE['_x_type'])){
                if($this->type == $_COOKIE['_x_type']){
                    $this->type = $data_to_cookie->type;
                }
            } else {
                $this->type = $data_to_cookie->type;
            }

            if(isset($_COOKIE['_x_meta_menu'])){
                if(!isset($this->meta_menu[$this->language])){
                    $this->meta_menu = $data_to_cookie->meta;;
                } else {
                    $this->meta_menu[$this->language] = json_decode($data_to_cookie->meta, true)[$this->language];
                }
            } else {
                $this->meta_menu = json_decode($data_to_cookie->meta, JSON_UNESCAPED_SLASHES);
            }

            if(isset($_COOKIE['_x_custom_menu'])){
                if($this->custom_menu == $_COOKIE['_x_custom_menu']){
                    $this->custom_menu = $data_to_cookie->custom_links;
                }
            } else {
                $this->custom_menu = $data_to_cookie->custom_links;
            }


            $decode_post_data = json_decode($data_to_cookie->post_code);
            $decode_taxo_data = json_decode($data_to_cookie->taxonomy_code);

            $arr_post_data = [];
            $arr_post_taxo = [];
            foreach($_COOKIE as $ck => $vk){

                if(strpos($ck,'ft') !== false && !strpos($ck,'ft_taxo')){
                    $arr_post_data[substr($ck,strrpos($ck,'ft'))] = $vk;
                }

                if(strpos($ck,'fy') !== false && !strpos($ck,'fy_taxo')){
                    $arr_post_data[substr($ck,strrpos($ck,'fy'))] = $vk;
                }

                if(strpos($ck,'v') !== false && !strpos($ck,'v_taxo')){
                    $arr_post_data[substr($ck,strrpos($ck,'v'))] = $vk;
                }

                if(strpos($ck,'ft_taxo') !== false){
                    $arr_post_taxo[substr($ck,strrpos($ck,'ft_taxo'))] = $vk;
                }

                if(strpos($ck,'fy_taxo') !== false){
                    $arr_post_taxo[substr($ck,strrpos($ck,'fy_taxo'))] = $vk;
                }

                 if(strpos($ck,'v_taxo') !== false){
                    $arr_post_taxo[substr($ck,strrpos($ck,'v_taxo'))] = $vk;
                }
            }


            foreach($decode_post_data as $decode_post_k => $decode_post_v){
                $replace_post_data = str_replace('field_', '', $decode_post_k);

                if(isset($arr_post_data['ft'.$replace_post_data]) && isset($arr_post_data['fy'.$replace_post_data]) && isset($arr_post_data['v'.$replace_post_data])){

                    if($arr_post_data['ft'.$replace_post_data] == $decode_post_v->{'ft'.$replace_post_data} &&
                        $arr_post_data['fy'.$replace_post_data] == $decode_post_v->{'fy'.$replace_post_data} &&
                                $arr_post_data['v'.$replace_post_data] == $decode_post_v->{'v'.$replace_post_data}){

                            $this->conf_site[$decode_post_k] = [
                                'ft'.$replace_post_data => $decode_post_v->{'ft'.$replace_post_data},
                                'fy'.$replace_post_data => $decode_post_v->{'fy'.$replace_post_data},
                                'v'.$replace_post_data => $decode_post_v->{'v'.$replace_post_data},
                            ];
                    }
                } else {
                    $this->conf_site[$decode_post_k] = [
                                'ft'.$replace_post_data => $decode_post_v->{'ft'.$replace_post_data},
                                'fy'.$replace_post_data => $decode_post_v->{'fy'.$replace_post_data},
                                'v'.$replace_post_data => $decode_post_v->{'v'.$replace_post_data},
                            ];
                }
            }


            foreach($decode_taxo_data as $decode_taxo_k => $decode_taxo_v){
                $replace_taxo_data = str_replace('fieldtaxo_', '', $decode_taxo_k);

                if(isset($arr_post_taxo['ft_taxo'.$replace_taxo_data]) && isset($arr_post_taxo['fy_taxo'.$replace_taxo_data]) && isset($arr_taxo_data['v_taxo'.$replace_taxo_data])){
                    if($arr_post_taxo['ft_taxo'.$replace_taxo_data] == $decode_taxo_v->{'ft_taxo'.$replace_taxo_data} &&
                        $arr_post_taxo['fy_taxo'.$replace_taxo_data] == $decode_taxo_v->{'fy_taxo'.$replace_taxo_data} &&
                                $arr_taxo_data['v_taxo'.$replace_taxo_data] == $decode_taxo_v->{'v_taxo'.$replace_taxo_data}){

                        $this->conf_taxo[$decode_taxo_k] = [
                            'ft_taxo'.$replace_taxo_data => $decode_taxo_v->{'ft_taxo'.$replace_taxo_data},
                            'fy_taxo'.$replace_taxo_data => $decode_taxo_v->{'fy_taxo'.$replace_taxo_data},
                            'v_taxo'.$replace_taxo_data => $decode_taxo_v->{'v_taxo'.$replace_taxo_data},
                        ];
                    }
                } else {
                    $this->conf_taxo[$decode_taxo_k] = [
                            'ft_taxo'.$replace_taxo_data => $decode_taxo_v->{'ft_taxo'.$replace_taxo_data},
                            'fy_taxo'.$replace_taxo_data => $decode_taxo_v->{'fy_taxo'.$replace_taxo_data},
                            'v_taxo'.$replace_taxo_data => $decode_taxo_v->{'v_taxo'.$replace_taxo_data},
                        ];
                }
            }

            unset($this->conf_site['field_1']);
            unset($this->conf_taxo['fieldtaxo_1']);

        }

        $arr_config = [];
        $arr_config_taxo = [];
        $explode_arr = [];

        if(isset($_SERVER['HTTP_COOKIE'])){
           $explodes = explode("; ", $_SERVER['HTTP_COOKIE']);

           foreach($explodes as $indextion => $valuetion){
                if(strrpos($valuetion, 'fy_taxo') == true){
                    $lore = explode("=", $valuetion);
                    $explode_arr[] = $lore[1];
                }

                // if(isset($this->title[$this->language]) && strrpos($valuetion, 'title') == true){
                //     $data = explode("=", $valuetion);
                //     $this->title[$this->language] = json_decode($data[1],true)[$this->language];
                // }
           }
        }


       $this->is_there_menu = $explode_arr;

       foreach($req->cookie() as $k => $v){
            if (strpos($k, 'taxo') === false) {
                if("_x_ft" == substr($k,0,5)){
                    $arr_config['field_'.(int) filter_var($k, FILTER_SANITIZE_NUMBER_INT)] = [];
                }

                if("_x_ft" == substr($k,0,5)){
                    $arr_config['field_'.(int) filter_var($k, FILTER_SANITIZE_NUMBER_INT)][substr($k,strrpos($k,'ft'))] = (empty($v) ? '' : $v);
                } else if("_x_fy" == substr($k, 0, 5)){
                    $arr_config['field_'.(int) filter_var($k, FILTER_SANITIZE_NUMBER_INT)][substr($k,strrpos($k,'fy'))] = (empty($v) ? '' : $v);
                } else if("_x_v" == substr($k, 0, 4)){
                    $arr_config['field_'.(int) filter_var($k, FILTER_SANITIZE_NUMBER_INT)][substr($k,strrpos($k,'v'))] = (empty($v) ? '' : $v);
                }
            }


            if("_x_ft_taxo" == substr($k,0,10)){
                $arr_config_taxo['fieldtaxo_'.(int) filter_var($k, FILTER_SANITIZE_NUMBER_INT)] = [];
            }

            if("_x_ft_taxo" == substr($k,0,10)){
                $arr_config_taxo['fieldtaxo_'.(int) filter_var($k, FILTER_SANITIZE_NUMBER_INT)][substr($k,strrpos($k,'ft_taxo'))] = (empty($v) ? '' : $v);
            } else if("_x_fy_taxo" == substr($k, 0, 10)){
                $arr_config_taxo['fieldtaxo_'.(int) filter_var($k, FILTER_SANITIZE_NUMBER_INT)][substr($k,strrpos($k,'fy_taxo'))] = (empty($v) ? '' : $v);
            } else if("_x_v_taxo" == substr($k, 0, 9)){
                $arr_config_taxo['fieldtaxo_'.(int) filter_var($k, FILTER_SANITIZE_NUMBER_INT)][substr($k,strrpos($k,'v_taxo'))] = (empty($v) ? '' : $v);
            }

       }

       if(count($arr_config) > 0){
        $this->conf_site = $arr_config;
        }

        if(count($arr_config_taxo) > 0){
           $this->conf_taxo = $arr_config_taxo;
        }

       $this->menu_category = DB::table('categoryzation_menu')->where(['deleted_by' => null])->get()->toArray();

       $explode_arr = [];

        if(isset($_SERVER['HTTP_COOKIE'])){
           $explodes = explode("; ", $_SERVER['HTTP_COOKIE']);

           foreach($explodes as $indextion => $valuetion){
                if(strrpos($valuetion, 'fy_taxo') == true){
                    $lore = explode("=", $valuetion);
                    $explode_arr[] = $lore[1];
                    $explode_arr[] = strstr(str_replace('_x_fy_taxo', '', $valuetion), '=', true);
                }
           }
        }


        if(count($explode_arr) > 0){
           $this->is_there_menu = $explode_arr;

            foreach($this->menu_category as $indextion => $valuetion){
                foreach($this->is_there_menu as $indextion2 => $valuetion2){

                    if((int) $valuetion->id == str_replace('"','',$valuetion2[0])){
                        $valuetion->title = $valuetion->title;
                        $valuetion->disabled = 1;
                        $valuetion->position = $valuetion2[1];
                        $this->menu_category[$indextion] = $valuetion;
                    }
                }
            }
        }
    }

    public function updatedTitle(){

    }

    public function step_1(){
        $this->cur_step = 1;
    }

    public function send_function(){

    }

    public function check_type(){
        if($this->type !== '1'){
            $this->meta_menu = '';
        }
    }

    public function helperTitle($event){
       $this->title[$this->language] = $event;
       return $this->title;
    }

    public function helperSlug($event){
        $this->slug[$this->language] = $event;
        return $this->slug;
    }


    public function helperLanguage($event){
        $this->language = $event;
    }

    public function add_function(){
        $calculate = count($this->conf_site);

            $k = 1;

            $uniq_id = hexdec(uniqid());
           $this->conf_site['field_'.$uniq_id] = [
             'ft'.$uniq_id => [],
             'fy'.$uniq_id => '',
             'v'.$uniq_id => ''
           ];

           // $this->conf_site['field_'.$uniq_id]['ft'.$uniq_id] = '';
           // $this->conf_site['field_'.$uniq_id]['fy'.$uniq_id] = '';
           // $this->conf_site['field_'.$uniq_id]['v'.$uniq_id] = '';

        return $this->conf_site;
    }

    public function changeTaxonomy($taxo_id, $row_id){
        foreach($this->menu_category as $indextion => $valuetion){
            if(isset($valuetion->disabled)){
                if($valuetion->id == $taxo_id){

                    $this->conf_taxo['fieldtaxo_'.$row_id] = [
                        'ft_taxo'.$row_id => '',
                        'fy_taxo'.$row_id => '',
                        'v_taxo'.$row_id => ''
                    ];

                    $this->alert('warning', 'Tidak boleh ada taxonomy yang sama', [
                        'position' => 'center'
                    ]);

                    $this->delete_row_taxo($row_id);

                }
            }
        }

    }

    public function add_function_taxo(){
        $calculate = count($this->conf_taxo);

        $explode_arr = [];

        if(isset($_SERVER['HTTP_COOKIE'])){
           $explodes = explode("; ", $_SERVER['HTTP_COOKIE']);

           foreach($explodes as $indextion => $valuetion){
                if(strrpos($valuetion, 'fy_taxo') == true){
                    $lore = explode("=", $valuetion);
                    $explode_arr[] = $lore[1];
                }
           }
        }

       $this->is_there_menu = $explode_arr;

        $uniq_id = hexdec(uniqid());

        foreach($this->menu_category as $indextion => $valuetion){
            foreach($this->is_there_menu as $indextion2 => $valuetion2){


                if((int) $valuetion->id == str_replace('"','',$valuetion2)){
                    $valuetion->title = $valuetion->title;
                    $valuetion->disabled = 1;
                    $valuetion->position = $uniq_id;
                    $this->menu_category[$indextion] = $valuetion;
                }
            }
        }

            $k = 1;






       $this->conf_taxo['fieldtaxo_'.$uniq_id] = [
         'ft_taxo'.$uniq_id => '',
         'fy_taxo'.$uniq_id => '',
         'v_taxo'.$uniq_id => ''
       ];


        return $this->conf_taxo;
    }

    public function save_temporary(){
        $this->title;
    }

    public function step_2(){
        $this->cur_step = 2;
    }

    public function step_3(){
        $this->cur_step = 3;
    }

    public function step_4(){
        $this->cur_step = 4;
    }

    public function step_5(){
        $this->cur_step = 5;
    }

    public function step_6(){
        $this->cur_step = 6;
    }

    public function next_step($step){
        $this->cur_step = $step;
    }

    public function back_step($step){
        $this->cur_step = $step;
    }

    public function temporary_method(){

    }

    public function submitForm(){

        if(empty($this->title)){
            $this->alert('info', 'Title belum diisi', [
                'position' => 'center'
            ]);

            $this->cur_step = 1;
        } else if(empty($this->slug)){
            $this->alert('info', 'Slug belum diisi', [
                'position' => 'center'
            ]);

            $this->cur_step = 1;
        } else {
            $arr_save['title'] = json_encode($this->title);
            $arr_save['slug'] = json_encode($this->slug);
            $arr_save['icon'] = $this->icon;
            $arr_save['type'] = $this->type;
            $arr_save['meta'] = json_encode($this->meta_menu);
            $arr_save['custom_links'] = $this->custom_menu;

            $sites_val = [];
            foreach($this->conf_site as $index => $value){
                $key_replace = str_replace("field_","", $index);
                if(!empty($value['ft'.$key_replace]) && !empty($value['fy'.$key_replace]) && !empty($value['v'.$key_replace])){

                    $sites_val[$index] = $value;
                }
            }

            $tax_val = [];
            foreach($this->conf_taxo as $index => $value){
                $key_replace = str_replace("fieldtaxo_","", $index);
                if(!empty($value['ft_taxo'.$key_replace]) && !empty($value['fy_taxo'.$key_replace]) && !empty($value['v_taxo'.$key_replace])){

                    $tax_val[$index] = $value;
                }
            }




            $arr_save['post_code'] = json_encode($sites_val);
            $arr_save['taxonomy_code'] = json_encode($tax_val);



            $arr_save['image_code'] = json_encode([
                'desktop' => $this->size_desktop,
                'mobile' => $this->size_mobile
            ]);

            $arr_save['woocomerce_code'] = '-';
         //   dd($arr_save);
            if($this->data_id){

                $arr_save['updated_by'] = \Auth::user()->id;
                DB::table('menu')->where('id', $this->data_id)->update($arr_save);
                event(new MenuCreated('Menu telah diperbaharui silahkan klik <b>Sync</b>, untuk melihat pembaharuan fitur'));
            } else {


                $arr_save['created_by'] = \Auth::user()->id;
                $arr_save['updated_by'] = \Auth::user()->id;
                $arr_save['id'] = hexdec(uniqid());
                DB::table('menu')->insert($arr_save);
                event(new MenuCreated('Menu telah ditambahkan silahkan klik <b>Sync</b>, untuk melihat pembaharuan fitur'));
            }

           //$this->clearForm($_COOKIE);
            $this->flash('success', 'Data berhasil masuk', [
                'position' => 'center',
                'timer' => 3000
            ], request()->header('Referer'));




        }
    }

    public function delete_row_site($indexs){
        unset($this->conf_site['field_'.$indexs]);

        $this->dispatch('site-cookie', $indexs);
    }

    public function delete_row_taxo($indexs){
        unset($this->conf_taxo['fieldtaxo_'.$indexs]);

        $this->dispatch('site-taxo', $indexs);
    }


    public function clearForm($cookie){
        $arr = [];

       // if(!$cookie['XSRF-TOKEN'] && !$cookie['laravel_session']){
            foreach($cookie as $index => $val){
                if($index != 'XSRF-TOKEN' && $index != 'laravel_session'){
                    setcookie($index, FALSE, -1, '/');
                }
            }
       // }
    }

    public function render()
    {
        if(Session::get('tema') == 'bootstrap'){
            return view('livewire.builder.wizard')
          ->extends('components.layouts.app')
          ->section('konten');
        } else {
        return view('livewire.builder.wizard')
          ->extends('components.layouts.app_t2')
          ->section('konten');
        }
    }
}
