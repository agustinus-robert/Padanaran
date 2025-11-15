<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\PostingImageDatatables;
use Livewire\Component;



class PostingImageDatatable extends Component
{
    // public function clickEdit($id){
    //     dd('ok');
    //    // $this->dispatch('post-created')->self();
    // }

    public function render(PostingImageDatatables $postingImageDataTable)
    {
        $data['trash'] = (isset($_GET['trash']) ? $_GET['trash'] : 0);
        $data['menu_id'] = (isset($_GET['id_menu']) ? $_GET['id_menu'] : 0);
        $data['id'] = (isset($_GET['id']) ? $_GET['id'] : 0);

        return $postingImageDataTable->render('admin::livewire.datatables.posting-image-datatable', $data);
    }
}
