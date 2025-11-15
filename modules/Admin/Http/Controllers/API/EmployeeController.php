<?php

namespace Modules\Admin\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Account\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Search empls.
     */
    public function search(Request $request)
    {
        return response()->success([
            'message' => 'Berikut adalah daftar karyawan berdasarkan kueri Anda.',
            'employees' => Employee::whereHas('user', fn ($user) => $user->search($request->get('q')))->limit(8)->get()->map(fn ($v) => [
                'id' => $v->id,
                'text' => $v->user->name
            ])
        ]);
    }
}
