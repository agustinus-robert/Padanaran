<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\ContactOrgDatatables;
use Livewire\Component;



class ContactOrgDatatable extends Component
{

    public function render(ContactOrgDatatables $contactOrgDatatables)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);

        return $contactOrgDatatables->render('admin::livewire.datatables.contact-org-datatable', $data);
    }
}
