<?php

namespace Modules\Administration\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Administration\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a analytical and statistical dashboard.
     */
    public function index()
    {
    	$user = auth()->user();

        return view('administration::dashboard.index', compact('user'));
    }
}
