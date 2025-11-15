<?php

namespace Modules\Admin\Http\Controllers\Employment;

use Illuminate\Http\Request;
use App\Models\Contract;
use Modules\Account\Models\Employee;
use Modules\HRMS\Models\EmployeeContract;
use Modules\Admin\Repositories\EmployeeContractRepository;
use Modules\Admin\Http\Requests\Employment\Contract\StoreRequest;
use Modules\Admin\Http\Requests\Employment\Contract\UpdateRequest;
use Modules\Admin\Http\Requests\Employment\Contract\WorkdaysUpdateRequest;
use Modules\Admin\Http\Controllers\Controller;

class ContractController extends Controller
{
    use EmployeeContractRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeContract::class);

        $contracts = EmployeeContract::with('contract', 'employee.user')
            ->search($request->get('search'))
            ->whenTrashed($request->get('trash'))
            ->paginate($request->get('limit', 10));
        $contracts_count = EmployeeContract::active()->count();

        return view('hrms::employment.contracts.index', compact('contracts', 'contracts_count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', EmployeeContract::class);

        $employee = Employee::withTrashed()->with('user')->find($request->old('employee_id', $request->get('employee')));
        $contracts = Contract::all();
        return view('admin::employment.contracts.create', compact('contracts', 'employee'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($contract = $this->storeEmployeeContract(Employee::find($request->input('employee_id')), $request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Perjanjian kerja baru dengan nomor <strong>' . $contract->kd . '</strong> berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeContract $contract, Request $request)
    {
        $this->authorize('show', $contract);

        $contract = $contract->load(['employee.user.meta', 'positions' => fn ($p) => $p->withTrashed()]);
        $addendums = $contract->getMeta('addendum') ?? [];

        return view('admin::employment.contracts.show', compact('contract', 'addendums'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function edit(EmployeeContract $contract, Request $request)
    {
        $this->authorize('update', $contract);

        return view('hrms::employment.contracts.edit', [
            'cmpcontracts' => Contract::all(),
            'employee' => Employee::withTrashed()->with('user')->find($request->old('employee_id', $request->get('employee'))),
            'contract' => $contract
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(EmployeeContract $contract, UpdateRequest $request)
    {
        $contract->fill($request->transformed()->toArray());
        if ($contract->save()) {
            return redirect()->next()->with('success', 'Perjanjian kerja baru dengan nomor <strong>' . $contract->kd . '</strong> berhasil diperbarui.');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeContract $contract)
    {
        $this->authorize('destroy', $contract);
        if ($contract = $this->destroyEmployeeContract($contract)) {
            return redirect()->next()->with('success', 'Perjanjian kerja <strong>' . $contract->kd . '</strong> berhasil dihapus');
        }
        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(EmployeeContract $contract)
    {
        $this->authorize('restore', $contract);
        if ($contract = $this->restoreEmployeeContract($contract)) {
            return redirect()->next()->with('success', 'Perjanjian kerja <strong>' . $contract->kd . '</strong> berhasil dipulihkan');
        }
        return redirect()->fail();
    }

    /**
     * Update workdays.
     */
    public function workdays(EmployeeContract $contract, WorkdaysUpdateRequest $request)
    {
        $this->authorize('update', $contract);
        $contract->setManyMeta($request->only('worktimes_default'));

        return redirect()->next()->with('success', 'Jam kerja <strong>' . $contract->kd . '</strong> berhasil diperbarui');
    }
}
