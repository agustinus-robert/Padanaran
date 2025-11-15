<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\PartnershipDatatables;
use Livewire\Component;



class PartnershipDatatable extends Component
{
    // public function clickEdit($id){
    //     dd('ok');
    //    // $this->dispatch('post-created')->self();
    // }

    public function render(PartnershipDatatables $partnershipsDataTable)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);

        return $partnershipsDataTable->render('admin::livewire.datatables.partnership-datatable', $data);
    }
}
