<?php

namespace Modules\Finance\Http\Controllers;

class DashboardController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function index()
    {
        return view('finance::index');
    }
}
