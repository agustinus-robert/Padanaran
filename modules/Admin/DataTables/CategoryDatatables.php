<?php

namespace Modules\Admin\DataTables;

use Illuminate\Http\Request;
use Modules\Admin\Models\Category;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CategoryDatatables extends DataTable
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
            ->addColumn('action', function ($row) {
                 $template = '';
                 $template .= view('admin::layouts_master.component.button_edit', array('id' => $row->id, 'update' => route('admin::builder.category.edit', ['category' => '?id='.$row->id.'&id_menu='.$row->id_menu_category])))->render();
                 $template .= view('admin::layouts_master.component.button_delete', array('id' => $row->id, 'delete' => route('admin::builder.category.destroy', ['category' => $row->id])))->render();
                 return $template;
             })
            ->rawColumns(['action']);
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
    public function query(Category $model, Request $request)
    {
        
        //return $model->newQuery();
        $str = $request->header('referer');
        $qs = parse_url($str, PHP_URL_QUERY);

        if(!empty($qs)){
            parse_str($qs, $output);
            
            $data = $model::where('id_menu_category', $output['cat_id'])->where('deleted_at', null);
            return $this->applyScopes($data);
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
            Column::make('title'),
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
