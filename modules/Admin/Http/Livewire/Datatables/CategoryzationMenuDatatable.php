<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\CategoryzationMenuDatatables;
use Livewire\Component;



class CategoryzationMenuDatatable extends Component
{

    public function render(CategoryzationMenuDatatables $categoryzationDatatables)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);

        return $categoryzationDatatables->render('admin::livewire.datatables.categoyzation-menu-datatable', $data);
    }
}
