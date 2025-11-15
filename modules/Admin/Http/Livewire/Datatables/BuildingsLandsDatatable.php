<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\BuildingsLandsDatatables;
use Livewire\Component;



class BuildingsLandsDatatable extends Component
{

    public function render(BuildingsLandsDatatables $buildingLandDatatables)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);
        

        if (strpos(\Request::getRequestUri(), 'lend_buildings_lands') !== false) {
            $data['status'] = 'lend';
        } else {
            $data['status'] = 'sell';
        }

        
        return $buildingLandDatatables->render('admin::livewire.datatables.buildings-lands-datatable', $data);
    }
}
