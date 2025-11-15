<?php

namespace Modules\Portal\DataTables;

use Modules\Admin\Models\SubNews;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Modules\Admin\Models\Partnership;
use Modules\Account\Models\User;
use DB;

class PartnershipHistoryDatatables extends DataTable
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
            ->addColumn('status', function($row){
                if($row->status == 0){
                    return '<span class="badge badge-light-danger fs-8 fw-bold">Pengajuan ditolak</span>';
                } else if($row->status == 1){
                    return '<span class="badge badge-light-warning fs-8 fw-bold">Menunggu Persetujuan</span>';
                } else if($row->status == 2){
                    return '<span class="badge badge-light-success fs-8 fw-bold">Disetujui</span>';
                } else if($row->status == 3){
                    return '<span class="badge badge-light-danger fs-8 fw-bold">Pembatalan Partnership</span>';
                }
            })
            ->addColumn('datetime', function($row){
                return tgl_indo(date('Y-m-d', strtotime($row->created_at))).' '.date('h:i:s', strtotime($row->created_at));
            })
            ->rawColumns(['status','datetime']);;
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
    public function query(Partnership $model)
    {
        
        //return $model->newQuery();
        if(isset($_GET['trash']) && $_GET['trash'] == 1){
            $data = $model::onlyTrashed();
        } else {
          //  $data = $model::select('status','user_id', DB::raw('MAX(id) AS id'))->groupBy('user_id');
            $data = $model::where(['user_id' => $_GET['id_user'], 'deleted_at' => null]);
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
            Column::make('status'),
            Column::make('datetime'),
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
