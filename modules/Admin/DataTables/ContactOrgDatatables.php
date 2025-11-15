<?php

namespace Modules\Admin\DataTables;

use Modules\Admin\Models\ContactOrganization;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ContactOrgDatatables extends DataTable
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
            ->addColumn('name', function($row){
                return $row->first_name.' '.$row->last_name;
            })
            ->addColumn('email', function($row){
                return $row->email;
            })
            ->addColumn('subject', function($row){
                return $row->subject;
            })
            ->addColumn('pesan', function ($row) {
                 $template = '';
                 $template .= view('admin::layouts_master.component.button_view', array('id' => $row->id))->render();
                 return $template;
             })
             ->addIndexColumn()
             ->rawColumns(['pesan']);
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
    public function query(ContactOrganization $model)
    {
        
        //return $model->newQuery();
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
                    ->setTableId('datatablesSimple')
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
            Column::make('name'),
            Column::make('email'),
            Column::make('subject'),
            Column::make('pesan'),
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
