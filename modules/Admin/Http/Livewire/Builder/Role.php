<?php

namespace Modules\Admin\Http\Livewire\Builder;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use DB;

class Role extends Component
{
    public $title;
    public $data_id;
    public $role = [];

    public function mount(Request $req){
        $id = $req->role;
        if(!empty($id) && is_string($id)){
            $this->data_id = $id;
            $arr = [];
            $data_to_cookie = DB::table('menu_has_role')->where('id', $id)->first();

            $this->title = $data_to_cookie->title;
            if(count(get_menu()) > 0){
                foreach(get_menu() as $index => $value){
                    $arr[$value->id] = $value->id;
                }

                $json = json_decode($data_to_cookie->json_menu, true);
                if(count($json) > 0){
                    foreach($json as $index => $value){
                        foreach($value as $index2 => $value2){
                            if(isset($arr[$index2])){
                              $this->role[$index][$index2] = $value2;  
                                // $this->role[$index] = 
                            }
                        }
                    }
                }
            }
        }
    }

    public function submit(){
        $arr_save['title'] = $this->title;
        $arr_save['json_menu'] = json_encode($this->role);

         if($this->data_id){
            $arr_save['updated_by'] = \Auth::user()->id;
            DB::table('menu_has_role')->where(['id' => $this->data_id])->update($arr_save); 
        } else {
            $arr_save['created_by'] = \Auth::user()->id;
            $arr_save['updated_by'] = \Auth::user()->id;
            DB::table('menu_has_role')->insert($arr_save);     
        }

        $url = route('admin::builder.role.index');
        return redirect($url)->with('msg', "Data berhasil disimpan");
    }

    public function render()
    {
        return view('admin::livewire.builder.role');   
    }
}
