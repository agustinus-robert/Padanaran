<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\VehcileDatatables;
use Livewire\Component;

class VehcileDatatable extends Component
{
    // public function clickEdit($id){
    //     dd('ok');
    //    // $this->dispatch('post-created')->self();
    // }

    public function render(VehcileDatatables $vechileDataTable)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);
        
        return $vechileDataTable->render('admin::livewire.datatables.vehcile-datatable', $data);
    }
}
