<?php

namespace Modules\HRMS\Http\Controllers\Employment;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyContract;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeContract;
use Modules\HRMS\Repositories\EmployeeContractRepository;
use Modules\HRMS\Http\Requests\Employment\Contract\Addendum\StoreRequest;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Repositories\EmployeeContractAddendumRepository;

class ContractAddendumController extends Controller
{
    use EmployeeContractAddendumRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(EmployeeContract $contract, Request $request)
    {
        return view('hrms::employment.contracts.addendums.create', [
            'departments'   => CompanyDepartment::all(),
            'cmpcontracts'  => CompanyContract::all(),
            'employee'      => Employee::withTrashed()->with('user')->find($request->old('employee_id', $request->get('employee'))),
            'contract'      => $contract
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeContract $contract, StoreRequest $request)
    {
        if ($contract = $this->storeAdendum($contract, $request->transformed()->toArray())) {
            if (isset($request->transformed()->toArray()['position'])) {
                if ($this->updatePosition($contract, $request->transformed()->toArray()['position'])) {
                    return redirect()->next()->with('success', 'Adendum perjanjian kerja baru dengan nomor <strong>' . $contract->kd . '</strong> berhasil dibuat.');
                }
                return redirect()->fail();
            }
            return redirect()->next()->with('success', 'Adendum perjanjian kerja baru dengan nomor <strong>' . $contract->kd . '</strong> berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeContract $contract, Request $request)
    {
        // $addendum = $contract->getMeta('addendums', [])[$id] ?? []
    }

    /**
     * Show the form for creating a new resource.
     */
    public function edit(EmployeeContract $contract, Request $request)
    {
        // $addendums = $contract->getMeta('addendums', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(EmployeeContract $contract, UpdateRequest $request)
    {
        // $addendums[$id] = $request->transformed()->toArray();
        // $contract->setMeta('addendums', array_values($addendums));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeContract $contract, Request $request)
    {
        $id = $request->get('addendum', 0);

        $addendums = $contract->getMeta('addendum') ?? [];

        if ($addendum = $addendums[$id] ?? false) {

            unset($addendum);

            $contract->setMeta('addendum', array_values($addendums));

            return redirect()->next()->with('success', 'Adendum perjanjian kerja baru dengan nomor <strong>' . $contract->kd . '</strong> berhasil dihapus.');
        }
        return redirect()->fail();
    }
}
