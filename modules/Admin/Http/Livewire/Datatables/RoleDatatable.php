<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\RoleDatatables;
use Livewire\Component;



class RoleDatatable extends Component
{
    // public function clickEdit($id){
    //     dd('ok');
    //    // $this->dispatch('post-created')->self();
    // }

    public function render(RoleDatatables $roleDataTable)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);

        return $roleDataTable->render('admin::livewire.datatables.role-datatable', $data);
    }
}
