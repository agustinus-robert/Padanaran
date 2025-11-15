<?php

namespace Modules\Admin\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\Services\DataTable;

class DataTableController extends Controller
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
