<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\CareerDataDatatables;
use Livewire\Component;



class CareerDataDatatable extends Component
{

    public function render(CareerDataDatatables $careerDataDatatables)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);

        return $careerDataDatatables->render('admin::livewire.datatables.career-data-datatable', $data);
    }
}
