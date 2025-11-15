<?php

namespace Modules\Admin\DataTables;

use Illuminate\Http\Request;
use Modules\Admin\Models\PostF;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use DB;

class PostingFormDatatables extends DataTable
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
            ->addColumn('title', function($row){
                return $row->title;
             })
             ->addColumn('created_at', function($row){
                return date('Y-m-d H:i:s', strtotime($row->created_at));
             })
            ->addColumn('action', function($row) use ($trash){
                
                 if($trash == 1){
                     // return '<button class="btn btn-sm btn-danger" title="Hapus Permanen" wire:click="$dispatch(\'building-land-delete-force\', { id: '.$row->id.'})"><span class="mdi mdi-delete"></span></button>';
                } else {
                     $template = '';
                     $template .= view('admin::layouts_master.component.button_list_form', array('id' => $row->id, 'lists' => route('admin::builder.posting_form_list.index')))->render();
                     $template .= view('admin::layouts_master.component.button_edit', array('id' => $row->id, 'update' => route('admin::builder.posting_form.edit', ['posting_form' => '?id_menu='.$row->menu_id.'&post_id='.$row->id])))->render();
                     $template .= view('admin::layouts_master.component.button_delete', array('id' => $row->id, 'delete' => route('admin::builder.posting_form.destroy', ['posting_form' => $row->id])))->render();
                     return $template;
                    
                   // return $html;  
                }
            })->rawColumns(['action']);
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
    public function query(PostF $model, Request $request)
    {
        $str = $request->header('referer');
        $qs = parse_url($str, PHP_URL_QUERY);

        if(!empty($qs)){
            parse_str($qs, $output);
            
            $data = $model::where('menu_id', $output['id_menu'])->where('deleted_at', null);
            return $this->applyScopes($data);
        }
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
            Column::make('title'),
            Column::make('created_at'),
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
