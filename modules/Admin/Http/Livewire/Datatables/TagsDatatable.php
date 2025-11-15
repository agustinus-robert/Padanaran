<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\TagsDatatables;
use Livewire\Component;



class TagsDatatable extends Component
{

    public function render(TagsDatatables $tagsDatatables)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);

        return $tagsDatatables->render('admin::livewire.datatables.tag-datatable', $data);
    }
}
