<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\PostingFormListDatatables;
use Livewire\Component;



class PostingFormListDatatable extends Component
{

    public function render(PostingFormListDatatables $postingFormListDatatables)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);

        return $postingFormListDatatables->render('admin::livewire.datatables.posting-form-datatable-list', $data);
    }
}
