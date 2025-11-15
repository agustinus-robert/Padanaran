<?php

namespace Modules\Admin\DataTables;

use Illuminate\Http\Request;
use Modules\Admin\Models\BuildingsLandsFloor;
use Modules\Admin\Models\Floor;
use Modules\Admin\Models\Room;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BuildingsLandsFloorDatatables extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query, Request $request)
    {
        if(isset($request->building_id)){
            $building_id = $request->building_id;
        }

        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('name', function($row){
                return $row->name;
            })
            ->addColumn('forheit_num', function($row) use ($building_id){
                $abs_diff = '';
                $get_id = BuildingsLandsFloor::where(['floor_id' => $row->id, 'building_sell_id' => $building_id, 'forheit_status' => null])->first();

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
            ->addColumn('eod_lend', function($row) use ($building_id){
                $effectiveDate = '';

                $get_id = BuildingsLandsFloor::where(['floor_id' => $row->id, 'building_sell_id' => $building_id, 'forheit_status' => null])->first();

                if(!empty($get_id->date_start) && empty($get_id->forheit_slice)){
                    $effectiveDate = date('Y-m-d', strtotime("+".$get_id->month_period." months", strtotime($get_id->date_start)));
                } else {
                    $effectiveDate = '-';
                }


                return $effectiveDate;
            })
            ->addColumn('forheit', function($row) use ($building_id){
                $html = '';

                $get_id = BuildingsLandsFloor::where(['floor_id' => $row->id, 'building_sell_id' => $building_id, 'forheit_status' => null])->first();

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
                            $html .= '<button class="btn btn-sm btn-outline-danger" wire:click="$dispatch(\'floor-on-room-forheit\', { id: '.$row->id.', sell_id : '.$building_id.' })">Denda</button>';
                        }
                        
                    } else {
                        $html .= '<button class="btn btn-sm btn-outline-secondary" disabled>Denda</button>';
                    }
                } else {
                    $html .= '<button class="btn btn-sm btn-outline-secondary" disabled>Denda</button>';
                }

                return $html;
            })
            ->addColumn('action', function($row) use ($building_id){
               $countRoom = Room::where('floor_id', $row->id)->count();
               
               $countAvailableRoom = Room::where(function ($query) use ($row) {
                    $query->where('floor_id', '=', $row->id)
                 ->whereNull('is_sell')
                 ->where('floor_id', '=', $row->id)
                 ->whereNull('is_lend');
                })->count();

               
               $countLendRoom = Room::where(['floor_id' => $row->id, 'is_lend' => 1])->count();

               $html = '';

               if($countRoom == $countAvailableRoom){
                
                    if($countRoom == 0){
                        $html .= '<button class="btn btn-sm btn-outline-secondary" disabled><i class="far fa-clipboard" aria-hidden="true" title="Ruangan Belum didaftarkan"></i></button>';
                    } else {
                        $html .= '<button class="btn btn-sm btn-outline-primary" wire:click="$dispatch(\'floor-on-room-choose\', { id: '.$row->id.', sell_id : '.$building_id.' })"><i class="far fa-clipboard" aria-hidden="true" title="Pinjam Semua Lantai"></i></button>';
                    }
                } else {
                    if($countRoom == 0){
                        $html .= '<button class="btn btn-sm btn-outline-secondary" disabled><i class="far fa-clipboard" aria-hidden="true" title="Ruangan Belum didaftarkan"></i></button>';
                    } else if($countRoom == $countLendRoom){
                        $html .= '<button class="btn btn-sm btn-outline-warning" wire:click="$dispatch(\'floor-on-room-back\', { id: '.$row->id.', sell_id : '.$building_id.' })"><i class="fa fa-arrow-left" aria-hidden="true" title="Kembalikan semua ruangan pada lantai"></i></button>';
                    } else {
                        $html .= '<button class="btn btn-sm btn-outline-secondary" disabled><i class="far fa-clipboard" aria-hidden="true" title="Semua ruangan terpinjam di lantai ini"></i></button>';
                    }
                }

                $html .= '<button class="btn btn-sm btn-outline-info" wire:click="$dispatch(\'floor-one-room\', { id: '.$row->id.', sell_id : '.$building_id.' })"><i class="fas fa-door-closed" aria-hidden="true" title="Pinjam Ruangan"></i></button>';

                // $html .= '<button class="btn btn-sm btn-outline-danger"  wire:click="$dispatch(\'floor-one\', { id: '.$row->id.', sell_id : '.$building_id.' })"><i class="fas fa-door-closed" aria-hidden="true" title="Pinjam Ruangan"></i></button>';

                return $html;
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
    public function query(Floor $model, Request $request)
    {
        
        //return $model->newQuery();
        if($building_id = $request->get('building_transaction_id')){
            $data = $model::where('building_id', $building_id);
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
                    ->setTableId('building-land-floor-table')
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
