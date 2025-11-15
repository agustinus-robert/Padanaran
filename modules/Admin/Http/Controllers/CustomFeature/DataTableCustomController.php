<?php
namespace Modules\Admin\Http\Controllers\CustomFeature;

use Illuminate\Http\Request;
use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\Services\DataTable;

class DataTableCustomController extends Controller
{

    protected $dataTable;

    public function __construct(DataTable $dataTable)
    {
        $this->dataTable = $dataTable;
    }

    public function index()
    {
        return $this->dataTable->ajax();
    }
}
