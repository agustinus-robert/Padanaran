<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\NewSubDatatables;
use Livewire\Component;



class NewSubDatatable extends Component
{

    public function render(NewSubDatatables $newSubDatatables)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);

        return $newSubDatatables->render('admin::livewire.datatables.new-sub-datatable', $data);
    }
}
