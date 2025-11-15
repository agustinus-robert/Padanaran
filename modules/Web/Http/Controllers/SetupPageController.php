<?php

namespace Modules\Web\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\FrontEndController;
use Modules\Reference\Http\Controllers\Controller;
use App\Models\CentralTenant;

class SetupPageController extends Controller
{
    public function index()
    {
        return view('web::wizard.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'data_json' => 'required',
            'your_name' => 'required',
            'domain' => 'required',
            'email' => 'required',
            'your_password' => 'required',
            'confirm_password' => 'required'
        ]);

        $data = json_decode($validated['data_json'], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['data_json' => 'Format JSON tidak valid']);
        }

        $tenant = CentralTenant::create([
            'name' => $validated['your_name'],
            'email' => $validated['email'],
            'domain' => $validated['domain'],
            'database' => uniqid('db_', true),
            'status' => 1
        ]);
    
        foreach($data as $key => $value){
            foreach($value['items'] as $items => $item){
                $tenant->setMeta($key, $item);
            }
        }

        return redirect()
            ->route('web::finish.setup.page')
            ->with('success', 'Silahkan kelola admin anda');
    }

    public function finish(Request $request){
        return view('web::wizard.finish');
    }
}
