<?php

namespace Modules\Admin\DataTables;

use Modules\Admin\Models\CareerData;
use Modules\Admin\Models\Post;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CareerDataDatatables extends DataTable
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
            ->addColumn('job_name', function($row){
                return get_content_json(Post::find($row->id))['id']['title'];
            })
            ->addColumn('name', function($row){
                return $row->name;
            })
            ->addColumn('phone', function($row){
                return $row->phone;
            })
            ->addColumn('email', function($row){
                return $row->email;
            })
            ->addColumn('address', function($row){
                return $row->address;
            })
            ->addColumn('file', function($row){
                return '<a href="">'.'Lihat Dokumen'.'</a>';
            })
            ->rawColumns(['job_name', 'name', 'phone', 'email', 'address', 'file']);
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
    public function query(CareerData $model)
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
                    ->setTableId('simpleDatatable')
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
            Column::make('job_name'),
            Column::make('name'),
            Column::make('phone'),
            Column::make('email'),
            Column::make('address'),
            Column::make('file'),
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
