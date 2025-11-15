<?php

namespace Modules\HRMS\Http\Controllers\Employment;

use Arr;
use Auth;
use Illuminate\Http\Request;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Http\Requests\Employment\Additional\StoreRequest;
use Modules\HRMS\Http\Controllers\Controller;

class AdditionalController extends Controller
{
    private $metaKeys = [
        'additional_days', 'additional_possition', 'additional_range'
    ];

    /**
     * Show the form for creating a new resource.
     */
    public function create(Employee $employee, Request $request)
    {
        $this->authorize('store', Employee::class);

        return view('hrms::employment.additionals.create', [
            'employee' => $employee,
            'departments' => CompanyDepartment::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Employee $employee, StoreRequest $request)
    {
        if ($employee) {
            $employee->setManyMeta(Arr::only($request->transformed()->toArray(), $this->metaKeys));
            Auth::user()->log('menambahkan pekerjaan tambahan kepada ' . $employee->user->name . ' <strong>[ID: ' . $employee->id . ']</strong>', Employee::class, $employee->id);
            return redirect()->next()->with('success', 'Pekerjaan tambahan ' . $employee->user->name . ' berhasil ditambahkan.');
        }
        return redirect()->fail();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function destroy(Employee $employee)
    {
        if ($employee) {
            $employee->removeManyMeta($this->metaKeys);
            Auth::user()->log('menghapus pekerjaan tambahan ' . $employee->user->name . ' <strong>[ID: ' . $employee->id . ']</strong>', Employee::class, $employee->id);
            return redirect()->next()->with('success', 'Pekerjaan tambahan ' . $employee->user->name . ' berhasil dihapus.');
        }
        return redirect()->fail();
    }
}
