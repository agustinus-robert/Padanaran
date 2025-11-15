<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\PostingDatatables;
use Livewire\Component;



class PostingDatatable extends Component
{
    // public function clickEdit($id){
    //     dd('ok');
    //    // $this->dispatch('post-created')->self();
    // }

    public function render(PostingDatatables $postingDataTable)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);
        $data['user_id'] = auth()->user()->id;

        return $postingDataTable->render('admin::livewire.datatables.posting-datatable', $data);
    }
}
