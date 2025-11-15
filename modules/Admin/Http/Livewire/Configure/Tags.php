<?php

namespace Modules\Admin\Http\Livewire\Configure;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Http\Request;
use Cookie;
use Redirect;
use DB;


class Tags extends Component
{

    public $title;
    public $icon;
    public $data_id;
    public $tags = [];
    public $count_tags = 0;

    public function mount(Request $req){
        $id = $req->tag;
        $this->tags = DB::table("tags")->get()->toArray();

        if(!empty($id) && is_string($id)){
            $this->data_id = $id;
            $data_to_tags = DB::table('tags')->where('id', $id)->first();

            $this->title = $data_to_tags->title;
            $this->icon = $data_to_tags->icon;
        }
    }

    public function submitForm(){
        $arr_save['title'] = $this->title;
        $arr_save['slug']   = strtolower(str_replace(' ','-', $this->title));
        $arr_save['icon']   = $this->icon;
        $arr_save['created_by'] = 1;
        $arr_save['updated_by'] = 1;

        if($this->data_id){
            DB::table('tags')->where('id', $this->data_id)->update($arr_save);
        } else {
            $arr_save['id'] = hexdec(uniqid());
            DB::table('tags')->insert($arr_save);
        }

           //$this->clearForm($_COOKIE);
        //admin::configure.tags.index
        $url = route('admin::configure.tags.index');
        return redirect($url)->with('msg', "Data berhasil disimpan");
    }

    public function changeTags($nm_tags){
        if(!empty($nm_tags)){
            $this->count_tags = DB::table("post")->where('tags', 'like', '%' . $nm_tags . '%')->count();
        } else {
            $this->count_tags = 0;
        }
    }

     public function destroy($id)
    {
        //destroy
        DB::table('tags')->where('id',$id)->delete();

        //flash message
        Session::flash('msg', "Data berhasil dihapus");
       // session()->flash('msg', 'Data Berhasil Dihapus.');

        //redirect
        return redirect()->route('tags_cfg.index');
    }

    public function render()
    {
        return view('admin::livewire.configure.tag');
    }
}
