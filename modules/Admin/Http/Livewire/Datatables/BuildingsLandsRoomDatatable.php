<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\BuildingsLandsRoomDatatables;
use Livewire\Component;
use Modules\Admin\Models\BuildingsLandsSell;



class BuildingsLandsRoomDatatable extends Component
{

    public function render(BuildingsLandsRoomDatatables $buildingRoomFloorDatatables)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);
        $data['building_floor'] = $_GET['floor_id'];
        $data['sell_id'] = $_GET['building_sell'];
        return $buildingRoomFloorDatatables->render('admin::livewire.datatables.building-lands-room-datatable', $data);
    }
}
