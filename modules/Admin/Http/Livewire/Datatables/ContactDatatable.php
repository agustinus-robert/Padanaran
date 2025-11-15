<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\ContactDatatables;
use Livewire\Component;



class ContactDatatable extends Component
{

    public function render(ContactDatatables $contactDatatables)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);

        return $contactDatatables->render('admin::livewire.datatables.contact-datatable', $data);
    }
}
