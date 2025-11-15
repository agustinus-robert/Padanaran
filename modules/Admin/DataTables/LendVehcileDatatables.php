<?php

namespace Modules\Admin\DataTables;

use Modules\Admin\Models\Vehcile;
use Modules\Admin\Models\VehcileLend;
use Modules\Admin\Models\Units;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LendVehcileDatatables extends DataTable
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
            ->addColumn('forheit_num', function($row) {
                $abs_diff = '';
                $get_id = $row->id;

                if(!empty($row->start_date)){
                    $date_now = date('Y-m-d', strtotime(now()));
                    $ending = date('Y-m-d', strtotime($row->end_date));
                    $earlier = new \DateTime($ending); 
                    $later = new \DateTime($date_now);
                    $diff = $later->diff($earlier)->format("%R%a");

                    if($diff > 0 && empty($row->forheit_slice)){
                        $abs_diff = $later->diff($earlier)->format("%a").' Hari'; //3
                    } else {
                        $abs_diff = '-';
                    }
                } else {
                    $abs_diff = '-';
                }

                return $abs_diff;
            })
            ->addColumn('eod_lend', function($row){
                $effectiveDate = '';

                $get_id = $row->id;

                if(!empty($row->end_date) && empty($row->forheit_slice)){
                    $effectiveDate = date('Y-m-d', strtotime($row->end_date));
                } else {
                    $effectiveDate = '-';
                }


                return $effectiveDate;
            })
            ->addColumn('forheit', function($row){
                $html = '';

                $get_id = $row->id;

                if(!empty($row->start_date)){
                    $date_now = date('Y-m-d', strtotime(now()));
                    $ending = date('Y-m-d', strtotime($row->end_date));
                    
                    $earlier = new \DateTime($ending); 
                    $later = new \DateTime($date_now);
                    
                    $diff = $later->diff($earlier)->format("%R%a");
                    if($diff > 0){
                        if(!empty($row->forheit_slice)){
                            $html .= '<button disabled class="btn btn-sm btn-outline-success">Denda Dibayar</button>';
                        } else {              
                            $html .= '<button class="btn btn-sm btn-outline-danger" wire:click="$dispatch(\'lend-on-vehcile-forheit\', { id: '.$row->id.' })">Denda</button>';
                        }
                        
                    } else {
                        $html .= '<button class="btn btn-sm btn-outline-secondary" disabled>Denda</button>';
                    }
                } else {
                    $html .= '<button class="btn btn-sm btn-outline-secondary" disabled>Denda</button>';
                }

                return $html;
            })
            ->addColumn('action', function($row){
                $lend = Vehcile::find($row->vehcile_id);
                
                if(empty($lend->is_lend) && empty($row->forheit_price)){
                    return '<p>Denda Belum dibayar</p>';
                } else {
                    if(!empty($row->forheit_slice) && $row->forheit_slice > 0){
                        return 'Transaksi Selesai';
                    } else {
                        return '<button class="btn btn-sm btn-outline-warning" wire:click="$dispatch(\'transaction-vehcile-restore\', { id: '.$row->id.',trans_id : '.$row->id.'})"><i class="fa fa-arrow-left" aria-hidden="true" title="Kembalikan kendaraan ini"></i></button>';    
                    }
                    
                }
            })
            ->rawColumns(['forheit_num', 'eod_lend', 'forheit', 'action']);
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
    public function query(VehcileLend $model)
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
                    ->setTableId('lend-vehcile-table')
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
            Column::make('forheit_num'),
            Column::make('eod_lend'),
            Column::make('forheit'),
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
