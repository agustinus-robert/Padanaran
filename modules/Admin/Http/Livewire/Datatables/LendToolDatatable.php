<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\LendToolDatatables;
use Livewire\Component;

class LendToolDatatable extends Component
{
    // public function clickEdit($id){
    //     dd('ok');
    //    // $this->dispatch('post-created')->self();
    // }

    public function render(LendToolDatatables $LendToolDataTable)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);
        
        return $LendToolDataTable->render('admin::livewire.datatables.lend-tool-datatable', $data);
    }
}
