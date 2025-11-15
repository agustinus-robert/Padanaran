<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\ToolSellDatatables;
use Livewire\Component;

class ToolSellDatatable extends Component
{
    // public function clickEdit($id){
    //     dd('ok');
    //    // $this->dispatch('post-created')->self();
    // }

    public function render(ToolSellDatatables $toolsellDataTable)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);
        
        return $toolsellDataTable->render('admin::livewire.datatables.tool-sell-datatable', $data);
    }
}
