<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\LendVehcileDatatables;
use Livewire\Component;

class LendVehcileDatatable extends Component
{
    // public function clickEdit($id){
    //     dd('ok');
    //    // $this->dispatch('post-created')->self();
    // }

    public function render(LendVehcileDatatables $LendVechileDataTable)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);
        
        return $LendVechileDataTable->render('admin::livewire.datatables.lend-vehcile-datatable', $data);
    }
}
