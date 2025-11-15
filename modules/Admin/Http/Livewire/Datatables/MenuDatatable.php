<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\MenuDatatables;
use Livewire\Component;



class MenuDatatable extends Component
{

    public function render(MenuDatatables $menuDatatables)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);

        return $menuDatatables->render('admin::livewire.datatables.menu-datatable', $data);
    }
}
