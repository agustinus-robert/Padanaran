<?php

namespace Modules\Portal\DataTables;

use Modules\Donation\Models\UserInformation;
use Modules\Portal\Models\Event;
use Modules\Admin\Models\Post;
use Modules\Donation\Models\Payment;
use Modules\Donation\Models\PaymentNotification;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;
use Session;

class VolunteerDatatables extends DataTable
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
            ->addColumn('name_event', function($row){
                //1811038194284839
                $post = Post::where(['id' => $row->post_id])->get()->first();
                return get_content_json($post)['id']['title'];
            })
            ->addColumn('start_date', function($row){
                $post = Post::where(['id' => $row->post_id])->get()->first();
                return tgl_indo(get_content_json($post)['id']['post0']);
            })
            ->addColumn('end_date', function($row){
                $post = Post::where(['id' => $row->post_id])->get()->first();
                return tgl_indo(get_content_json($post)['id']['post1']);
            })
            ->rawColumns(['name_event', 'start_date', 'end_date']);
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
    public function query(Event $model)
    {
        
        //return $model->newQuery();
        
        if(isset($_GET['trash']) && $_GET['trash'] == 1){
            $data = $model::onlyTrashed();
        } else {
            $data = $model::where(['user_id' => $_GET['id_user'], 'deleted_at' => null]);
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
            Column::make('name_event'),
            Column::make('start_date'),
            Column::make('end_date')
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
