<?php

namespace Modules\Admin\Http\Livewire\Builder;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use DB;

class Categoryzation extends Component
{

    public $title;
    public $is_parent;
    public $description;
    public $id_menu_category;
    public $data_id;

    #[Validate('image|max:1024')]
    public $photo;
    public $sh_photo;

    public function mount(Request $req){
        $this->id_menu_category = $req->cat_id;
        $id = $req->id;
        if(!empty($id) && is_string($id)){
            $this->data_id = $id;
            $data_to_cookie = DB::table('categoryzation')->where('id', $id)->first();
            $this->sh_photo = $data_to_cookie->location.'/'.$data_to_cookie->image;

            $this->title = $data_to_cookie->title;
            $this->description = $data_to_cookie->description;

            if($data_to_cookie->parent == 1){
                $this->is_parent = true;
            } else {
                $this->is_parent = false;
            }

            $this->id_menu_category = $data_to_cookie->id_menu_category;
        }
    }

    public function submitForm(){
        // $location = 'image_category/'.$this->id_menu_category.'/'.uniqid();

        $arr_save['title'] = $this->title;
        $arr_save['slug'] = strtolower(str_replace(' ','-', $this->title));
        $arr_save['status'] = 1;

        // if(!empty($this->photo)){
        //     $arr_save['image'] = $this->photo->getFilename();
        //     $arr_save['location'] = $location;
        // }

        if($this->is_parent == true){
            $arr_save['parent'] = 1;
        } else {
            $arr_save['parent'] = 0;
        }

        // if(!empty($this->photo)){
        //     $this->photo->storeAs($location, $this->photo->getFilename(), 'public');
        // }

        $arr_save['description'] = $this->description;
        $arr_save['id_menu_category'] = $this->id_menu_category;

         if($this->data_id){
            $arr_save['updated_by'] = \Auth::user()->id;
            DB::table('categoryzation')->where(['id' => $this->data_id, 'id_menu_category' => $this->id_menu_category])->update($arr_save);
        } else {
            $arr_save['id'] = hexdec(uniqid());
            $arr_save['created_by'] = \Auth::user()->id;
            $arr_save['updated_by'] = \Auth::user()->id;
            DB::table('categoryzation')->insert($arr_save);
        }

           //$this->clearForm($_COOKIE);
       // Session::flash();
        $url = route('admin::builder.category.index').'?cat_id='.$this->id_menu_category;
        return redirect($url)->with('msg', "Data berhasil disimpan");

    }

    public function render()
    {
        return view('admin::livewire.builder.categoryzation');
    }
}
