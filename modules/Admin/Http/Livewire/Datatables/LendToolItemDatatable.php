<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\LendToolItemDatatables;
use Livewire\Component;

class LendToolItemDatatable extends Component
{
    // public function clickEdit($id){
    //     dd('ok');
    //    // $this->dispatch('post-created')->self();
    // }

    public function render(LendToolItemDatatables $LendToolItemDataTable)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);
        $data['lend_id'] = (isset($_GET['lend_id']) ? $_GET['lend_id'] : 0);
        
        return $LendToolItemDataTable->render('admin::livewire.datatables.lend-tool-item-datatable', $data);
    }
}
