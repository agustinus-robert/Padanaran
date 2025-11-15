<?php

namespace Modules\Admin\DataTables;

use Illuminate\Http\Request;
use Modules\Admin\Models\BuildingsLandsSell;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BuildingsLandsDatatables extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query, Request $request)
    {
        if($request->trash == 0){
            $trash = 0;
        } else {
            $trash = 1;
        }

        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('invoice', function($row){
                return $row->invoice;
            })
            ->addColumn('certificate', function($row){
                return '<a href="'.asset('uploads/'.$row->file_sk).'">Download</a>';
            })
            ->addColumn('is_land', function($row){
                $html = '';
                if($row->is_back_land == 1){
                    $html .= "<b>Tanah Sudah dikembalikan</b>";
                } else {
                    if(!empty($row->id_land)){
                    $html .= '<button class="btn btn-sm btn-success text-center" wire:click="$dispatch(\'back-land-transaction\', { id: '.$row->id.', id_land: '.$row->id_land.'})"><i class="fa fa-plus-square" style="color:white;" aria-hidden="true"></i></button>';
                    } else {
                        $html .= '<b>Tidak ada tanah</b>';
                    }
                }
                
                return $html;
            })
            ->addColumn('is_building', function($row){
                $html = '';
                if(!empty($row->id_building)){
                    $html .= '<button class="btn btn-sm btn-info text-center" wire:click="$dispatch(\'building-land-transaction\', { id: '.$row->id.'})"><i class="fa fa-plus-square" style="color:white;" aria-hidden="true"></i></button>';
                } else {
                    $html .= '<b>Tidak ada bangunan</b>';
                }

                return $html;
            })
            ->addColumn('action', function($row) use ($trash){
                
                 if($trash == 1){
                     return '<button class="btn btn-sm btn-danger" title="Hapus Permanen" wire:click="$dispatch(\'building-land-delete-force\', { id: '.$row->id.'})"><span class="mdi mdi-delete"></span></button>';
                } else {
                    $html = '';
                    $html .= '<button class="btn btn-sm btn-warning" wire:click="$dispatch(\'building-land-edited\', { id: '.$row->id.'})"><i class="fa fa-pencil" aria-hidden="true"></i></button>';

                    // $html .= '<button class="btn btn-sm btn-danger" wire:click="$dispatch(\'building-land-delete\', { id: '.$row->id.'})"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                    
                    return $html;  
                }
            })
            ->rawColumns(['invoice', 'certificate', 'is_land', 'is_building', 'action']);
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
    public function query(BuildingsLandsSell $model, Request $request)
    {
        if($request->status == 'lend'){
            if(isset($_GET['trash']) && $_GET['trash'] == 1){
                $data = $model::where('status', 2)->onlyTrashed();
            } else {
                $data = $model::where('status', 2);
            }
        } else {
            if(isset($_GET['trash']) && $_GET['trash'] == 1){
                $data = $model::where('status', 1)->onlyTrashed();
            } else {
                $data = $model::where('status', 1);
            }    
        }
        //return $model->newQuery();
        
        
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
                    ->setTableId('building-land-table')
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
            Column::make('certificate'),
            Column::make('is_land'),
            Column::make('is_building'),
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
