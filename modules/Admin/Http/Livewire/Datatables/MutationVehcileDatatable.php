<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\MutationVehcileDatatables;
use Livewire\Component;

class MutationVehcileDatatable extends Component
{
    // public function clickEdit($id){
    //     dd('ok');
    //    // $this->dispatch('post-created')->self();
    // }

    public function render(MutationVehcileDatatables $MutationVechileDataTable)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);
        
        return $MutationVechileDataTable->render('admin::livewire.datatables.mutation-vehcile-datatable', $data);
    }
}
