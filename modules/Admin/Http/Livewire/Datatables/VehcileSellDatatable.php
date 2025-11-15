<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\VehcileSellDatatables;
use Livewire\Component;

class VehcileSellDatatable extends Component
{
    // public function clickEdit($id){
    //     dd('ok');
    //    // $this->dispatch('post-created')->self();
    // }

    public function render(VehcileSellDatatables $vechileSellDataTable)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);
        
        return $vechileSellDataTable->render('admin::livewire.datatables.vehcile-sell-datatable', $data);
    }
}
