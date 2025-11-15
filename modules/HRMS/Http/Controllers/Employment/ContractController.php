<?php

namespace Modules\HRMS\Http\Controllers\Employment;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyMoment;
use Modules\Core\Models\CompanyContract;
use Modules\HRMS\Enums\WorkShiftEnum;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeContract;
use Modules\HRMS\Repositories\EmployeeContractRepository;
use Modules\HRMS\Http\Requests\Employment\Contract\StoreRequest;
use Modules\HRMS\Http\Requests\Employment\Contract\UpdateRequest;
use Modules\HRMS\Http\Requests\Employment\Contract\WorkdaysUpdateRequest;
use Modules\HRMS\Http\Controllers\Controller;

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
            ->whereHas('employee', function ($q) {
              $q->where('grade_id', userGrades()); 
            })
            ->search($request->get('search'))
            ->whenTrashed($request->get('trash'))
            ->paginate($request->get('limit', 10));

        $contracts_count = EmployeeContract::whereHas('employee', function ($q) {
              $q->where('grade_id', userGrades()); 
            })->active()->count();

        return view('hrms::employment.contracts.index', compact('contracts', 'contracts_count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', EmployeeContract::class);

        $employee = Employee::where('grade_id', userGrades())->withTrashed()->with('user')->find($request->old('employee_id', $request->get('employee')));
        $contracts = CompanyContract::where('grade_id', userGrades())->get();
        return view('hrms::employment.contracts.create', compact('contracts', 'employee'));
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

        $period = $request->get('workdays_period', date('Y-m'));
        $contract = $contract->load(['employee.user.meta', 'positions' => fn($p) => $p->withTrashed()]);
        $addendums = $contract->getMeta('addendum') ?? [];

        $workdays = \Modules\Reference\Enums\DayEnum::cases();
        $workshifts = WorkShiftEnum::cases();
        $moments = CompanyMoment::holiday()->whenMonthOfYear($period)->get();

        $dates = [];
        for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($period)), date('Y', strtotime($period))); $i++) {
            $dates[] = $period . '-' . str_pad($i, 2, 0, STR_PAD_LEFT);
        }

        return view('hrms::employment.contracts.show', compact('contract', 'workdays', 'moments', 'dates', 'workshifts', 'addendums'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function edit(EmployeeContract $contract, Request $request)
    {
        $this->authorize('update', $contract);

        return view('hrms::employment.contracts.edit', [
            'cmpcontracts' => CompanyContract::all(),
            'employee' => Employee::where('grade_id', userGrades())->withTrashed()->with('user')->find($request->old('employee_id', $request->get('employee'))),
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
