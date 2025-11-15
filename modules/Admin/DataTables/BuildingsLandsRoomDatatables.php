<?php

namespace Modules\Admin\DataTables;

use Illuminate\Http\Request;
use Modules\Admin\Models\BuildingsLandsRoom;
use Modules\Admin\Models\Room;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BuildingsLandsRoomDatatables extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query, Request $request)
    {
        if(isset($request->sell_id)){
            $sell_id = $request->sell_id;
        }

        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('name', function($row){
                return $row->name;
            })
            ->addColumn('forheit_num', function($row) use ($sell_id){
                $abs_diff = '';
                $get_id = BuildingsLandsRoom::where(['room_id' => $row->id, 'floor_id' => $row->floor_id, 'building_sell_id' => $sell_id, 'forheit_status' => null])->first();

               // dd($get_id);

                if(!empty($get_id->date_start)){
                    $date_now = date('Y-m-d', strtotime(now()));
                    $ending = date('Y-m-d', strtotime("+".$get_id->month_period." months", strtotime($get_id->date_start)));
                    $earlier = new \DateTime($ending); 
                    $later = new \DateTime($date_now);
                    $diff = $later->diff($earlier)->format("%R%a");

                    if($diff > 0 && empty($get_id->forheit_slice)){
                        $abs_diff = $later->diff($earlier)->format("%a").' Hari'; //3
                    } else {
                        $abs_diff = '-';
                    }
                } else {
                    $abs_diff = '-';
                }

                return $abs_diff;
            })
            ->addColumn('eod_lend', function($row) use ($sell_id){
                $effectiveDate = '';

                $get_id = BuildingsLandsRoom::where(['room_id' => $row->id, 'floor_id' => $row->floor_id, 'building_sell_id' => $sell_id, 'forheit_status' => null])->first();

                if(!empty($get_id->date_start) && empty($get_id->forheit_slice)){
                    $effectiveDate = date('Y-m-d', strtotime("+".$get_id->month_period." months", strtotime($get_id->date_start)));
                } else {
                    $effectiveDate = '-';
                }


                return $effectiveDate;
            })
            ->addColumn('forheit', function($row) use ($sell_id){
                $html = '';

                $get_id = BuildingsLandsRoom::where(['room_id' => $row->id, 'floor_id' => $row->floor_id, 'building_sell_id' => $sell_id, 'forheit_status' => null])->first();

                if(!empty($get_id->date_start)){
                    $date_now = date('Y-m-d', strtotime(now()));
                    $ending = date('Y-m-d', strtotime("+".$get_id->month_period." months", strtotime($get_id->date_start)));
                    
                    $earlier = new \DateTime($ending); 
                    $later = new \DateTime($date_now);
                    
                    $diff = $later->diff($earlier)->format("%R%a");
                    if($diff > 0){
                        if(!empty($get_id->forheit_slice)){
                            $html .= '<button disabled class="btn btn-sm btn-outline-success">Denda Dibayar</button>';
                        } else {              
                            $html .= '<button class="btn btn-sm btn-outline-danger" wire:click="$dispatch(\'floor-on-room-forheit\', { id: '.$row->id.', sell_id : '.$sell_id.', floor_id: '.$row->floor_id.' })">Denda</button>';
                        }
                        
                    } else {
                        $html .= '<button class="btn btn-sm btn-outline-secondary" disabled>Denda</button>';
                    }
                } else {
                    $html .= '<button class="btn btn-sm btn-outline-secondary" disabled>Denda</button>';
                }

                return $html;
            })
            ->addColumn('action', function($row) use ($sell_id){
                $isSell = $row->is_sell;
                $isLend = $row->is_lend;

                if(empty($isSell) || empty($isLend)){
                    return '<button class="btn btn-sm btn-outline-primary" wire:click="$dispatch(\'transaction-room-choose\', { id: '.$row->id.',sell_id : '.$sell_id.', status : \'back_sell\' })"><i class="far fa-clipboard" aria-hidden="true" title="Transaksi Ruang"></i></button>';
                } else {
                    return '<button class="btn btn-sm btn-outline-warning" wire:click="$dispatch(\'transaction-room-restore\', { id: '.$row->id.',sell_id : '.$sell_id.', floor_id : '.$row->floor_id.', status : \'back_sell\' })"><i class="fa fa-arrow-left" aria-hidden="true" title="Kembalikan ruangan ini"></i></button>';
                }
            })
            ->rawColumns(['name','forheit_num','eod_lend','forheit','action']);
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
    public function query(Room $model, Request $request)
    {
        
        //return $model->newQuery();
        if($floor_id = $request->get('floor_id')){
            $data = $model::where('floor_id', $floor_id);
            return $this->applyScopes($data);
        }
        
        
        // 
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('building-land-room-table')
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
            Column::make('action'),
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
