<?php

namespace Modules\Poz\Http\Livewire\Datatables;

use Modules\Poz\DataTables\CustomDatatables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Http\Request;
use Livewire\Component;

class CustomDatatable extends Component
{
    public $tableArr = [];
    public $outlet = '';
    public function mount($arr, Request $request)
    {
        $this->outlet = $request->query('outlet');
        $this->tableArr = $arr;
    }

    public function render(Builder $builder)
    {
        $module = new CustomDatatables();
        $html = $module->columnBuilder($this->tableArr, $builder);
        $title = $this->tableArr['title'] ?? '';
        $outlet = $this->outlet;
        $menu = '';

        if(isset($this->tableArr['menu'])){
            $menu = $this->tableArr['menu'];
        }

        return view('poz::livewire.datatables.custom-datatable', compact('html', 'title','menu', 'outlet'));
    }
}
