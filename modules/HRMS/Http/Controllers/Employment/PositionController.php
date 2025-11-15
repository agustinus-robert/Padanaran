<?php

namespace Modules\HRMS\Http\Controllers\Employment;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeContract;
use Modules\HRMS\Models\EmployeePosition;
use Modules\HRMS\Repositories\EmployeePositionRepository;
use Modules\HRMS\Http\Requests\Employment\Position\StoreRequest;
use Modules\HRMS\Http\Requests\Employment\Position\UpdateRequest;
use Modules\HRMS\Http\Controllers\Controller;

class PositionController extends Controller
{
    use EmployeePositionRepository;

    /**
     * Show the form for creating a new resource.
     */
    public function create(EmployeeContract $contract, Request $request)
    {
        $this->authorize('store', EmployeePosition::class);

        $departments = CompanyDepartment::all();
        return view('hrms::employment.positions.create', compact('contract', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeContract $contract, StoreRequest $request)
    {
        if ($position = $this->storeEmployeePosition($contract, $request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Jabatan ' . $position->name . ' berhasil ditambahkan di perjanjian kerja nomor <strong>' . $contract->kd . '</strong> berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function edit(EmployeePosition $position, Request $request)
    {
        $this->authorize('update', $position);

        $departments = CompanyDepartment::all();
        $position->load('contract');

        return view('hrms::employment.positions.edit', compact('departments', 'position'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(EmployeePosition $position, UpdateRequest $request)
    {
        if ($position = $this->updateEmployeePosition($position, $request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Jabatan ' . $position->name . ' berhasil diperbarui di perjanjian kerja nomor <strong>' . $position->contract->kd . '</strong> berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function destroy(EmployeePosition $position)
    {
        if ($position = $this->removeEmployeePosition($position)) {
            return redirect()->next()->with('success', 'Jabatan ' . $position->name . ' berhasil dihapus di perjanjian kerja nomor <strong>' . $position->contract->kd . '</strong> berhasil dibuat.');
        }
        return redirect()->fail();
    }
}
