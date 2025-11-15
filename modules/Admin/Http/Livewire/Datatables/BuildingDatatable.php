<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\BuildingDatatables;
use Livewire\Component;



class BuildingDatatable extends Component
{

    public function render(BuildingDatatables $buildingDatatables)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);

        return $buildingDatatables->render('admin::livewire.datatables.building-datatable', $data);
    }
}
