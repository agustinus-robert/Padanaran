<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\ToolDatatables;
use Livewire\Component;



class ToolDatatable extends Component
{
    // public function clickEdit($id){
    //     dd('ok');
    //    // $this->dispatch('post-created')->self();
    // }

    public function render(ToolDatatables $toolDataTable)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);
        return $toolDataTable->render('admin::livewire.datatables.tool-datatable', $data);
    }
}
