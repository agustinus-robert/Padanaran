<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\SupplierDatatables;
use Livewire\Component;



class SupplierDatatable extends Component
{
    // public function clickEdit($id){
    //     dd('ok');
    //    // $this->dispatch('post-created')->self();
    // }

    public function render(SupplierDatatables $supplierDataTable)
    {
        
        return $supplierDataTable->render('admin::livewire.datatables.supplier-datatable');
    }
}
