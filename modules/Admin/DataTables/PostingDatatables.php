<?php

namespace Modules\Admin\DataTables;

use Illuminate\Http\Request;
use Modules\Admin\Models\Post;
use Modules\Admin\Models\Menu;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use DB;

class PostingDatatables extends DataTable
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

        $query->where('created_by', $request->user);

        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('title', function ($row) {
                return get_content_json($row)['id']['title'];
            })
            ->addColumn('slug', function ($row) {
                return get_content_json($row)['id']['slug'];
            })
            ->addColumn('created_at', function ($row) {
                return date('Y-m-d H:i:s', strtotime($row->created_at));
            })
            ->addColumn('action', function ($row) use ($trash) {

                if ($trash == 1) {
                    // return '<button class="btn btn-sm btn-danger" title="Hapus Permanen" wire:click="$dispatch(\'building-land-delete-force\', { id: '.$row->id.'})"><span class="mdi mdi-delete"></span></button>';
                } else {
                    $menu_status = Menu::where('id', $row->menu_id)->first();

                    $template = '';

                    if (empty($menu_status->edit) || $menu_status->edit == 1) {
                        $template .= view('admin::layouts_master.component.button_edit', array('id' => $row->id, 'update' => route('admin::builder.posting.edit', ['posting' => '?id_menu=' . $row->menu_id . '&post_id=' . $row->id])))->render();
                    }

                    if (empty($menu_status->album) || $menu_status->album == 1) {
                        $template .= view('admin::layouts_master.component.button_image', array('id' => $row->id, 'btnimage' => route('admin::builder.posting_image.index') . '?id_menu=' . $row->menu_id . '&post_id=' . $row->id))->render();
                    }

                    if (empty($menu_status->video) || $menu_status->video == 1) {
                        $template .= view('admin::layouts_master.component.button_video', array('id' => $row->id, 'btnvideo' => route('admin::builder.posting_video.index') . '?id_menu=' . $row->menu_id . '&post_id=' . $row->id))->render();
                    }

                    if (empty($menu_status->delete) || $menu_status->delete == 1) {
                        $template .= view('admin::layouts_master.component.button_delete', array('id' => $row->id, 'delete' => route('admin::builder.posting.destroy', ['posting' => $row->id])))->render();
                    }

                    return $template;
                }
            })->addColumn('status', function ($row) {
                $text = '';
                $status_now = 0;
                $sch = DB::table('schedule_post')->where('post_id', $row->id)->latest('id')->first();

                if (isset($sch->schedule_on) && empty($sch->deleted_at) && strtotime(date('Y-m-d H:i', strtotime(date('Y-m-d H:i:s')))) < strtotime(date('Y-m-d H:i', strtotime($sch->schedule_on . ' ' . $sch->timepicker)))) {
                    $text .= 'Post soon publish';
                } else if ($row->status == '2') {
                    $text .= 'Publish';
                    $status_now = 2;
                } else if ($row->status == '3') {
                    $text .= 'Draft';
                    $status_now = 3;
                }


                return view('admin::layouts_master.component.button_status_edit', array('id' => $row->id, 'status' => $status_now, 'sch_post' => $sch))->render() . $text;
            })->rawColumns(['status', 'action']);
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
    public function query(Post $model, Request $request)
    {
        $str = $request->header('referer');
        $qs = parse_url($str, PHP_URL_QUERY);

        if (!empty($qs)) {
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
            Column::make('slug'),
            Column::make('created_at'),
            Column::make('status'),
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
