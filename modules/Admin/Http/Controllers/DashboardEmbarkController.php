<?php

namespace Modules\Admin\Http\Controllers;

use Modules\Account\Models\UserLog;

class DashboardEmbarkController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function index()
    {
        return view('admin::embark_dashboard', [
            'recent_activities' => UserLog::whereModelableType('Modules\Admin\Models\InventoryProposal')->with('user.meta')->latest()->limit(5)->get()
        ]);
    }
}
