<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\CategoryDatatables;
use Livewire\Component;



class CategoryzationDatatable extends Component
{

    public function render(CategoryDatatables $categoryzationDatatables)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);

        return $categoryzationDatatables->render('admin::livewire.datatables.categoryzation-datatable', $data);
    }
}
