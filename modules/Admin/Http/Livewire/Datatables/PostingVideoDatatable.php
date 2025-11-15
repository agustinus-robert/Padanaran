<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\PostingVideoDatatables;
use Livewire\Component;



class PostingVideoDatatable extends Component
{
    // public function clickEdit($id){
    //     dd('ok');
    //    // $this->dispatch('post-created')->self();
    // }

    public function render(PostingVideoDatatables $postingImageDataTable)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);
        $data['menu_id'] = (isset($_GET['menu_id']) ? $_GET['menu_id'] : 0);
        $data['id'] = (isset($_GET['id']) ? $_GET['id'] : 0);

        return $postingImageDataTable->render('admin::livewire.datatables.posting-video-datatable', $data);
    }
}
