<?php

namespace Modules\Admin\Http\Livewire\Cfg;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Session;
use DB;

class TbPostingImage extends Component
{
    use WithPagination;

    public $menu_id;
    public $post_id;

    public function mount($id_menu, $id_posting){
        $this->menu_id = $id_menu;
        $this->post_id = $id_posting;
    }

    public function render()
    {
        $data['pagination'] = DB::table('post_image')->where(['menu_id' => $this->menu_id, 'post_id' => $this->post_id])->paginate(7);
        $data['menu'] = DB::table('post')->where('id', $this->post_id)->first();

        if(Session::get('tema') == 'bootstrap'){
            return view('livewire.cfg.tb-posting-image', ['data' => $data])
        ->extends('components.layouts.app')
        ->section('konten');
        } else {
            return view('livewire.cfg.tb-posting-image', ['data' => $data])
        ->extends('components.layouts.app_t2')
        ->section('konten');
        }
        
    }
}
