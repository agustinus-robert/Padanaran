<?php

namespace Modules\Admin\DataTables;

use Modules\Admin\Models\VehcileSell;
use Modules\Admin\Models\Units;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use DB;

class VehcileSellDatatables extends DataTable
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
            ->addColumn('inv', function($row){
                return $row->invoice;
            })
            ->addColumn('file_sk', function($row){
                return '<a href="'.asset($row->file_sk).'">Download</a>';
            })
            ->addColumn('date_sell', function($row){
                return  $row->date_sell;
            })
            ->addColumn('price_sell', function($row){
                return $row->price_sell;
            })
            ->rawColumns(['inv', 'file_sk', 'date_sell', 'price_sell']);
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
    public function query(VehcileSell $model)
    {
        if(isset($_GET['trash']) && $_GET['trash'] == 1){
            $data = $model::onlyTrashed();
        } else {
            $data = VehcileSell::select('vehcile_sell.id as id','vehcile_sell.invoice as invoice','vehcile_sell.file_sk as file_sk','vehcile_sell.date_sell as date_sell')
            ->selectRaw("SUM(vsi.vehcile_price) as price_sell")
            ->leftJoin('vehcile_sell_item as vsi','vsi.vehcile_sell_id','=','vehcile_sell.id')
            ->groupBy('id');
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
                    ->setTableId('vehcile-sell-table')
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
            Column::make('inv'),
            Column::make('file_sk'),
            Column::make('date_sell'),
            Column::make('price_sell'),
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
