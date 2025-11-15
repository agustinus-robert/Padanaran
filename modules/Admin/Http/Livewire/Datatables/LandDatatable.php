<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\LandDatatables;
use Livewire\Component;



class LandDatatable extends Component
{

    public function render(LandDatatables $landDatatables)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);

        return $landDatatables->render('admin::livewire.datatables.land-datatable', $data);
    }
}
