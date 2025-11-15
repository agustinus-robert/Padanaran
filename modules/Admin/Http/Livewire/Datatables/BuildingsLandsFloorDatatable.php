<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\BuildingsLandsFloorDatatables;
use Livewire\Component;
use Modules\Admin\Models\BuildingsLandsSell;
use Modules\Admin\Models\Floor;
use Modules\Admin\Models\Room;



class BuildingsLandsFloorDatatable extends Component
{ 
    public $form = [];

    public function render(BuildingsLandsFloorDatatables $buildingLandFloorDatatables)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);
        $data['building_transaction_id'] = BuildingsLandsSell::find($_GET['building_sell'])->id_building;
        $data['sell_id'] = $_GET['building_sell'];
        return $buildingLandFloorDatatables->render('admin::livewire.datatables.building-lands-floor-datatable', $data);
    }

     public function save(){
        dd($this->form);
    }
}
