<?php

namespace Modules\Admin\DataTables;

use Modules\Admin\Models\Vehcile;
use Modules\Admin\Models\VehcileLend;
use Modules\Admin\Models\ToolLend;
use Modules\Admin\Models\Units;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LendToolDatatables extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('invoice', function($row) {
                return $row->invoice;
            })
            ->addColumn('date_sk', function($row){
                return $row->date_sk;
            })
            ->addColumn('file_sk', function($row){
                return '<a href="'.asset($row->file_sk).'">Download Serifikat Bangunan</a>';
            })
            ->addColumn('action', function($row){
                $html = '<button class="btn btn-sm btn-outline-primary" wire:click="$dispatch(\'transaction-list-landTool\', { id: '.$row->id.'})"><i class="fa fa-arrow-right" aria-hidden="true" title="Pilih transaksi"></i></button>';

                return $html;
            })
            ->rawColumns(['invoice', 'date_sk', 'file_sk', 'action']);
            // ->editColumn('action', function($data){
            //      //return \Livewire::mount('admin::livewire.btn.actions', ['permission' => $data->id])->html();
            //     return \Livewire::mount('admin::livewire.btn.actions', $data)->html();
            // })
           // ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ToolLend $model)
    {
        if(isset($_GET['trash']) && $_GET['trash'] == 1){
            $data = $model::onlyTrashed();
        } else {
            $data = $model->newQuery();
        }
        
        return $this->applyScopes($data);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('lend-tool-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax('datatable?class=' . __CLASS__)
                    ->drawCallbackWithLivewire()
                    ->orderBy(1)
                    ->buttons(
                        Button::make('create'),
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    )
                    ->parameters([
                        'paging' => true,
                        'searching' => true,
                        'info' => false,
                        'searchDelay' => 350,
                    ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('DT_RowIndex'),
            Column::make('invoice'),
            Column::make('date_sk'),
            Column::make('file_sk'),
            Column::make('action')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    // protected function filename()
    // {
    //     return 'Supplier_' . date('YmdHis');
    // }
}
