<?php

namespace Modules\Admin\Http\Livewire\Configure;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use App\Events\MenuCategoriesCreated;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Http\Request;
use Cookie;
use Redirect;
use DB;

class CategoryzationName extends Component
{

    public $title;
    public $icon;
    public $data_id;
    public $category_listing = [];
    public $category_by_list = 0;
    public $category_by_list_post = 0;

    public function mount(Request $req){
        $id = $req->categoryzation_name;
        $get_category = DB::table('categoryzation_menu')->get()->toArray();

        if(count($get_category) > 0){
            $this->category_listing = $get_category;
        } else {
            $this->category_listing = [];
        }

        if(!empty($id) && is_string($id)){
            $this->data_id = $id;
            $data_to_cookie = DB::table('categoryzation_menu')->where('id', $id)->first();

            $this->title = $data_to_cookie->title;
            $this->icon = $data_to_cookie->icon;
        }
    }

    public function changeListCategory($id_category){
        if(!empty($id_category)){
            $ttl = DB::table("categoryzation")->where("id_menu_category",'=',$id_category)->count();
            $this->category_by_list = $ttl;
        } else {
            $this->category_by_list = 0;
        }
    }

    public function changeListToPostCategory($id_category){
        if(!empty($id_category)){
            $ttl = DB::table("post_has_category")
            ->join('categoryzation', 'categoryzation.id', '=', 'post_has_category.tags_id')
            ->join('categoryzation_menu', 'categoryzation_menu.id', '=', 'categoryzation.id_menu_category')
            ->where("categoryzation_menu.id",'=',$id_category)->count();

            $this->category_by_list_post = $ttl;
        } else {
            $this->category_by_list_post = 0;
        }
    }


    public function submitForm(){
        $arr_save['title'] = $this->title;
        $arr_save['slug']   = strtolower(str_replace(' ','-', $this->title));
        $arr_save['created_by'] = 1;
        $arr_save['updated_by'] = 1;

        $arr_save['icon'] = $this->icon;
        $arr_save['type'] = 3;

        if($this->data_id){
            $arr_save['updated_by'] = \Auth::user()->id;
            DB::table('categoryzation_menu')->where('id', $this->data_id)->update($arr_save);
        } else {
            $arr_save['created_by'] = \Auth::user()->id;
            $arr_save['updated_by'] = \Auth::user()->id;
            $arr_save['id'] = hexdec(uniqid());
            DB::table('categoryzation_menu')->insert($arr_save);
        }

           //$this->clearForm($_COOKIE);
        $url = route('admin::configure.categoryzation_name.index');
        return redirect($url)->with('msg', "Data berhasil disimpan");
    }

      public function destroy($id)
    {
        //destroy
        DB::table('categoryzation_menu')->where('id',$id)->delete();

        //flash message
        Session::flash('msg', "Data berhasil dihapus");
       // session()->flash('msg', 'Data Berhasil Dihapus.');

        //redirect
        return redirect()->route('ctg_cfg.index');
    }

    public function render()
    {
        return view('admin::livewire.configure.categoryzation-name');
    }
}
