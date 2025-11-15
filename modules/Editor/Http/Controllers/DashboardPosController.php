<?php

namespace Modules\Editor\Http\Controllers;
use Modules\Reference\Http\Controllers\Controller;

class DashboardPosController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function index()
    {
        return view('editor::dashboard');
    }
}
