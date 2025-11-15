<?php

namespace Modules\Admin\DataTables;

use Illuminate\Http\Request;
use Modules\Admin\Models\Vehcile;
use Modules\Admin\Models\VehcileLend;
use Modules\Admin\Models\ToolLend;
use Modules\Admin\Models\Tool;
use Modules\Admin\Models\ToolLendItem;
use Modules\Admin\Models\Units;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LendToolItemDatatables extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query, Request $request)
    {
        if(isset($request->lend)){
            $lend_id = $request->lend;
        }

      

        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('name', function($row){
                $name = Tool::find($row->tool_id);
                return $name->name_asset;
            })
            ->addColumn('forheit_num', function($row){
                $abs_diff = '';
                $get_id = $row;

               // dd($get_id);

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

                $get_id = $row;


                if(!empty($get_id->start_date) && empty($get_id->forheit_slice)){
                    $effectiveDate = date('Y-m-d', strtotime($get_id->end_date));
                } else {
                    $effectiveDate = '-';
                }


                return $effectiveDate;
            })
            ->addColumn('forheit', function($row){
                $html = '';

                $get_id = $row;

                if(!empty($get_id->start_date)){
                    $date_now = date('Y-m-d', strtotime(now()));
                    $ending = date('Y-m-d', strtotime($row->end_date));
                    
                    $earlier = new \DateTime($ending); 
                    $later = new \DateTime($date_now);
                    
                    $diff = $later->diff($earlier)->format("%R%a");
                    if($diff > 0){
                        if(!empty($get_id->forheit_slice)){
                            $html .= '<button disabled class="btn btn-sm btn-outline-success">Denda Dibayar</button>';
                        } else {              
                            $html .= '<button class="btn btn-sm btn-outline-danger" wire:click="$dispatch(\'floor-on-tool-forheit\', { id: '.$row->id.'})">Denda</button>';
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
                $isTool = Tool::find($row->tool_id);
              
                if(!empty($row->is_back)){
                    return 'Transaksi Selesai, Barang dikembalikan';
                } else if(!empty($row->forheit_slice) || $row->forheit_slice > 0){
                    return '<button class="btn btn-sm btn-outline-warning" wire:click="$dispatch(\'transaction-lend-tool-restore\', { id: '.$row->id.' })"><i class="fa fa-arrow-left" aria-hidden="true" title="Kembalikan ruangan ini"></i></button>';
                } else {
                    return '-';
                }
            })
            ->rawColumns(['name', 'forheit_num', 'eod_lend', 'forheit', 'action']);
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
    public function query(ToolLendItem $model, Request $request)
    {
        if(isset($_GET['trash']) && $_GET['trash'] == 1){
            $data = $model::onlyTrashed();
        } else {
            $data = $model->where('tool_lend_id', $request->lend);
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
                    ->setTableId('lend-tool-item-table')
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
