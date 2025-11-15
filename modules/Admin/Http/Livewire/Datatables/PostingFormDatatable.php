<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\PostingFormDatatables;
use Livewire\Component;



class PostingFormDatatable extends Component
{
    // public function clickEdit($id){
    //     dd('ok');
    //    // $this->dispatch('post-created')->self();
    // }

    public function render(PostingFormDatatables $postingDataTable)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);

        return $postingDataTable->render('admin::livewire.datatables.posting-form-datatable', $data);
    }
}
