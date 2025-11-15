<?php

namespace Modules\Admin\Http\Livewire\Datatables;

use Modules\Admin\DataTables\CustomDatatables;
use Yajra\DataTables\Html\Builder;
use Livewire\Component;



class CustomDatatable extends Component
{
    public $tableArr = [];
    public function mount($arr)
    {
        $this->tableArr = $arr;
    }

    public function render(Builder $builder)
    {
        $module = new CustomDatatables();
        $html = $module->columnBuilder($this->tableArr, $builder);

        return view('admin::livewire.datatables.custom-datatable', compact('html'));
    }
}
