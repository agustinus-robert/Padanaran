<?php

namespace Modules\Admin\Http\Livewire\Cfg;

use Livewire\Component;
use Livewire\WithPagination;
use DB;

class TbCategoryzationName extends Component
{
    public function render()
    {
        $data_table = DB::table('categoryzation_menu')->get()->toArray();

        return view('livewire.cfg.tb-categoryzation-name', ['posts' => $data_table]);
    }
}
