<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\FloorDatatables;
use Livewire\Component;



class FloorDatatable extends Component
{
    // public function clickEdit($id){
    //     dd('ok');
    //    // $this->dispatch('post-created')->self();
    // }

    public function render(FloorDatatables $floorDataTable)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);
        return $floorDataTable->render('admin::livewire.datatables.floor-datatable', $data);
    }
}
