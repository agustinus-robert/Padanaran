<?php

namespace Modules\Admin\DataTables;

use Illuminate\Http\Request;
use Modules\Admin\Models\Menu;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use DB;

class MenuDatatables extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query, Request $request)
    {
        if ($request->trash == 0) {
            $trash = 0;
        } else {
            $trash = 1;
        }

        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('title', function ($row) {
                return json_decode($row->title, true)['id'];
            })
            ->addColumn('slug', function ($row) {
                return json_decode($row->slug, true)['id'];
            })
            ->addColumn('action', function ($row) use ($trash) {

                $hasil_menu = DB::table('menu_order')->select("menu_text")->where("id", 1)->get()->first();
                $template = '';

                if ($row->type == '1') {

                    if (!empty($hasil_menu->menu_text)) {
                        $get_all = json_decode($hasil_menu->menu_text, true);
                        $arr_k = [];
                        foreach ($get_all as $index => $value) {
                            $arr_k[$value['id']] = $value;
                        }
                    }


                    if (isset($arr_k[$row->id]) && isset($arr_k[$row->id]['children']) && count($arr_k[$row->id]['children']) > 0) {
                        $template .= "parent_menu";
                    } else {
                        $template .= view('admin::layouts_master.component.button_edit', array('id' => $row->id, 'update' => route('admin::builder.menu.edit', ['menu' => '?id=' . $row->id])))->render();
                        $template .= view('admin::layouts_master.component.button_delete', array('id' => $row->id, 'delete' => route('admin::builder.menu.destroy', ['menu' => $row->id])))->render();
                    }
                } else {
                    $template .= view('admin::layouts_master.component.button_edit', array('id' => $row->id, 'update' => route('admin::builder.menu.edit', ['menu' => '?id=' . $row->id])))->render();
                    $template .= view('admin::layouts_master.component.button_delete', array('id' => $row->id, 'delete' => route('admin::builder.menu.destroy', ['menu' => $row->id])))->render();
                }
                return $template;
            })->addColumn('parent', function ($row) {
                $hasil_menu = DB::table('menu_order')->select("menu_text")->where("id", 1)->get()->first();

                $get_all = [];

                if (!empty($hasil_menu->menu_text)) {
                    $get_all = json_decode($hasil_menu->menu_text, true);
                }
                $hasil = '';
                $arr_k = [];
                $arr_l = [];
                $arr_z = [];

                //   dd($get_all);
                if (count($get_all) > 0) {
                    foreach ($get_all as $index => $value) {
                        $arr_z[$value['id']] = $value;
                    }
                }

                if ($row->type == 1) {
                    if (!isset($arr_z[$row->id])) {
                        $hasil = 'parent kosong';
                    } else if (isset($arr_z[$row->id]['children'])) {
                        if (isset(json_decode(get_menu_id($row->id)->title, true)['id'])) {
                            $hasil = json_decode(get_menu_id($row->id)->title, true)['id'];
                        }
                    } else if (!isset($arr_z[$row->id]['children'])) {
                        $hasil = 'parent kosong';
                    }
                }


                foreach ($get_all as $index => $value) {

                    if (isset($value['children'])) {
                        foreach ($value['children'] as $index2 => $value2) {
                            $arr_k[$value2['id']] = json_decode(get_menu_id($value['id'])->title, true)['id'];
                        }
                    }
                }

                if ($row->type == '2') {
                    if (isset($arr_k[$row->id])) {
                        $hasil = $arr_k[$row->id];
                    } else {
                        $hasil = 'child kosong';
                    }
                }

                return $hasil;
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
    public function query(Menu $model)
    {
        return $model->newQuery();
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
            Column::make('no'),
            Column::make('title'),
            Column::make('slug'),
            Column::make('parent'),
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
