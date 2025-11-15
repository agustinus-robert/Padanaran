<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\MutationToolDatatables;
use Livewire\Component;

class MutationToolDatatable extends Component
{
    // public function clickEdit($id){
    //     dd('ok');
    //    // $this->dispatch('post-created')->self();
    // }

    public function render(MutationToolDatatables $MutationToolDataTable)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);
        
        return $MutationToolDataTable->render('admin::livewire.datatables.mutation-tool-datatable', $data);
    }
}
