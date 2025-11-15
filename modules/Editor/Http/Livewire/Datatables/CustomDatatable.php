<?php

namespace Modules\Editor\Http\Livewire\Datatables;

use Modules\Pos\DataTables\CustomDatatables;
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
        $title = $this->tableArr['title'];
        return view('poz::livewire.datatables.custom-datatable', compact('html', 'title'));
    }
}
