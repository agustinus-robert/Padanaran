<?php

namespace Modules\Admin\Http\Livewire\Cfg;

use Livewire\Component;
use Livewire\WithPagination;
use DB;

class TbTags extends Component
{
    public function render()
    {
        $data_table = DB::table('tags')->get()->toArray();

        return view('livewire.cfg.tb-tags', ['posts' => $data_table]);
    }
}
