<?php

namespace Modules\Portal\DataTables;

use Modules\Donation\Models\UserInformation;
use Modules\Donation\Models\Donation;
use Modules\Donation\Models\Payment;
use Modules\Donation\Models\PaymentNotification;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Session;

class DonationDatatables extends DataTable
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
            ->addColumn('invoice', function($row){
                return $row->invoice_num;
            })
            ->addColumn('status', function($row){
               $getifNotif = PaymentNotification::where('invoice_num', $row->invoice_num)->first();
               if(isset($getifNotif->id)){
                    if(isset($getifNotif->transaction_status) && $getifNotif->transaction_status == 'settlement'){
                        return '<span class="badge badge-light-success">Approved</span>'; 
                    } else {
                        return '<span class="badge badge-light-warning">Pending</span>';
                    }
               } else {
                    return '<span class="badge badge-light-warning">Pending</span>';
               }
            })
            ->addColumn('action', function($row){
                return "<a href='".route('portal::guest.donation.show', ['donation' => $row->invoice_num])."'><i class='mdi mdi-hand-coin'></i></a>";
            })
            ->rawColumns(['invoice', 'status', 'action']);
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
    public function query(UserInformation $model)
    {
        
        //return $model->newQuery();
        if(isset($_GET['trash']) && $_GET['trash'] == 1){
            $data = $model::onlyTrashed();
        } else {
            $data = $model::where('email', $_GET['email']);
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
            Column::make('invoice'),
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
