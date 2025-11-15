<?php

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;

class DefaultDatatables extends DataTable
{


    public function query()
    {
        return collect([])->toBase(); // Data kosong sebagai fallback
    }
}
