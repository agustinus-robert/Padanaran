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


class PostingImage extends Component
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

    public function mount($id_menu, $id_posting, $id, Request $req){
        $this->menu_id = $id_menu;
        $this->post_id = $id_posting;

        if(!empty($id) && is_string($id)){
            $this->data_id = $id;
            $data_to_post = DB::table('post_image')->where('id', $id)->first();
            $category_to_post = DB::table('post_image_has_category')->where('id_category_image', $id)->get()->first();

            $this->title = $data_to_post->title;
            $this->sh_photo = $data_to_post->location.'/'.$data_to_post->image;
            if(isset($category_to_post->id_category_image)){
                $this->category = $category_to_post->id_category_image;
            }

            $this->content = $data_to_post->content;
        }
    }

    public function submitForm(){
        $location = 'image_posting/'.$this->menu_id.'/'.$this->post_id.'/'.uniqid();

        $arr_save['title'] = $this->title;
        $arr_save['slug'] = strtolower(str_replace(' ','-', $this->title));


        $arr_save['content'] = json_encode($this->content);


        $arr_save['menu_id'] = $this->menu_id;
        $arr_save['post_id'] = $this->post_id;
        $arr_save['created_by'] = 1;
        $arr_save['updated_by'] = 1;

        $arr_save['location'] = $location;
        $arr_save['image'] = $this->photo->getFilename();
        //$this->category
        $arr_save_category['id_category_image'] = 1;


        $this->photo->storeAs($location, $this->photo->getFilename(), 'public');

        if($this->data_id){
            $arr_save['updated_by'] = \Auth::user()->id;
            DB::table('post_image')->where(['id' => $this->data_id])->update($arr_save);
            $arr_save_category['updated_by'] = \Auth::user()->id;
            DB::table('post_image_has_category')->where(['id_image' => $this->data_id])->update($arr_save_category);
        } else {
           $hexas = hexdec(uniqid());
           $arr_save['created_by'] = \Auth::user()->id;
           $arr_save['updated_by'] = \Auth::user()->id;
           $arr_save['id'] = $hexas;
           $arr_save_category['id_image'] = $hexas;

           DB::table('post_image')->insert($arr_save);
           $arr_save_category['updated_by'] = \Auth::user()->id;
           $arr_save_category['created_by'] = \Auth::user()->id;
           DB::table('post_image_has_category')->insert($arr_save_category);
        }

        return redirect('/posting_image/'.$this->menu_id.'/'.$this->post_id)->with('msg', "Data Photo berhasil disimpan");

    }

    public function render()
    {
        $data = DB::table('menu')->where('id', $this->menu_id)->first();


        if(Session::get('tema') == 'bootstrap'){
            return view('livewire.cfg.posting-image', ['data' => $data])
        ->extends('components.layouts.app')
        ->section('konten');
        } else {
        return view('livewire.cfg.posting-image', ['data' => $data])
        ->extends('components.layouts.app_t2')
        ->section('konten');
        }
    }
}
