<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\RoomDatatables;
use Livewire\Component;



class RoomDatatable extends Component
{
    // public function clickEdit($id){
    //     dd('ok');
    //    // $this->dispatch('post-created')->self();
    // }

    public function render(RoomDatatables $roomDataTable)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);
        return $roomDataTable->render('admin::livewire.datatables.room-datatable', $data);
    }
}
