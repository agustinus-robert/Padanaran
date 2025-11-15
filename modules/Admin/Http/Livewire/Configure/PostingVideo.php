<?php

namespace Modules\Admin\Http\Livewire\Cfg;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Storage;
use Redirect;
use DB;


class PostingVideo extends Component
{
    use WithFileUploads;

    public $data_id;
    public $title;
    public $content = '';
    public $menu_id;
    public $photo;
    public $post_id;
    public $tags;
    public $sh_photo;
    public $category;
    public $embedvid;

    public function mount($id_menu, $id_posting, $id, Request $req){
        $this->menu_id = $id_menu;
        $this->post_id = $id_posting;

        if(!empty($id) && is_string($id)){
            $this->data_id = $id;
            $data_to_post = DB::table('post_video')->where('id', $id)->first();

            $this->title = $data_to_post->title;
            $this->content = $data_to_post->deskripsi;
            $this->embedvid = $data_to_post->link_embed;
        }
    }

    public function submitForm(){
        $location = 'image_posting/'.$this->menu_id.'/'.$this->post_id.'/'.uniqid();

        $arr_save['title'] = $this->title;
        $arr_save['slug'] = strtolower(str_replace(' ','-', $this->title));


        $arr_save['deskripsi'] = json_encode($this->content);
        $arr_save['link_embed'] = $this->embedvid;


        $arr_save['menu_id'] = $this->menu_id;
        $arr_save['post_id'] = $this->post_id;
        $arr_save['created_by'] = 1;
        $arr_save['updated_by'] = 1;

        if($this->data_id){
            $arr_save['updated_by'] = \Auth::user()->id;
            DB::table('post_video')->where(['id' => $this->data_id])->update($arr_save);
        } else {
           $hexas = hexdec(uniqid());
           $arr_save['created_by'] = \Auth::user()->id;
           $arr_save['updated_by'] = \Auth::user()->id;
           $arr_save['id'] = $hexas;

           DB::table('post_video')->insert($arr_save);
        }

        return redirect('/posting_video/'.$this->menu_id.'/'.$this->post_id)->with('msg', "Data Video berhasil disimpan");

    }

    public function render()
    {
        $data = DB::table('menu')->where('id', $this->menu_id)->first();


        if(Session::get('tema') == 'bootstrap'){
            return view('livewire.cfg.posting-video', ['data' => $data])
        ->extends('components.layouts.app')
        ->section('konten');
        } else {
        return view('livewire.cfg.posting-video', ['data' => $data])
        ->extends('components.layouts.app_t2')
        ->section('konten');
        }
    }
}
