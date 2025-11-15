<?php

namespace Modules\Finance\Http\Controllers\Service\Deduction;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Models\EmployeeDeduction;
use Modules\Finance\Http\Controllers\Controller;
use Modules\Finance\Http\Requests\Service\Deduction\Manage\StoreRequest;
use Modules\Finance\Http\Requests\Service\Deduction\Manage\UpdateRequest;
use Modules\HRMS\Enums\DeductionTypeEnum;
use Modules\HRMS\Models\Employee;

class ManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('finance::service.deduction.manage.index', [
            'start_at'    => ($start_at = $request->get('start_at', date('Y-m-01')) . ' 00:00:00'),
            'end_at'      => ($end_at = $request->get('end_at', date('Y-m-t')) . ' 23:59:59'),
            'departments' => CompanyDepartment::visible()->with('positions')->get(),
            'deductions'  => EmployeeDeduction::with('approvables.userable.position', 'employee.user')
                ->where(fn($q) => $q->whereHas(
                    'approvables',
                    fn($approvable) => $approvable->whereResult(ApprovableResultEnum::APPROVE)->whereBetween('updated_at', [$start_at, $end_at])->orderByDesc('level')->limit(1)
                )->orDoesntHave('approvables'))
                ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
                ->whenOnlyPending($request->get('pending'))
                ->search($request->get('search'))
                ->latest()
                ->paginate($request->get('limit', 10)),
            'deduction_count' => EmployeeDeduction::count(),

        ]);
    }

    /**
     * Create resource
     */
    public function create(Request $request)
    {
        $start_at  = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at    = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');
        $employees = Employee::with('user')->get();
        $employee  = Employee::find($request->get('empl_id'));
        $items     = !is_null($employee) ? $employee->salaryTemplate()->with(['items' => fn($s) => $s->whereIn('component_id', config('modules.finance.ref_deduction_item'))])->first()?->items ?? [] : [];

        return view('finance::service.deduction.manage.create', [
            'employees'  => $employees,
            'employee'   => $employee,
            'start_at'   => $start_at,
            'end_at'     => $end_at,
            'categories' => DeductionTypeEnum::cases(),
            'items'      => $items
        ]);
    }

    /**
     * Store a resource.
     */
    public function store(StoreRequest $request)
    {
        $deduction = new EmployeeDeduction($request->transformed()->toArray());
        if ($deduction->save()) {
            return redirect()->next()->with('success', 'Potongan atas nama <strong>' . $deduction->employee->user->name . '</strong> berhasil disimpan.');
        }
        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeDeduction $deduction)
    {
        $employee = $deduction->employee;
        $items = [];

        if (!is_null($employee)) {
            $salaryTemplate = $employee->salaryTemplate()->with([
                'items' => fn($s) =>
                $s->whereIn('component_id', config('modules.finance.ref_deduction_item'))
            ])->first();

            if (!is_null($salaryTemplate)) {
                $items = $salaryTemplate->items;
            }
        }

        return view('finance::service.deduction.manage.show', [
            'deduction' => $deduction->load('employee.user', 'approvables.userable.position'),
            'categories' => DeductionTypeEnum::cases(),
            'items' => $items
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeDeduction $deduction, UpdateRequest $request)
    {
        $deduction->fill($request->transformed()->toArray());
        if ($deduction->save()) {
            return redirect()->next()->with('success', 'Berhasil memperbarui detail potongan, terima kasih!');
        }
        return redirect()->back()->with('danger', 'Gagal memperbarui detail potongan, Hubungi IT!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeDeduction $deduction)
    {
        $tmp = $deduction;
        $deduction->delete();

        return redirect()->back()->with('success', 'Pengajuan reimbursement <strong>' . $tmp->employee->user->name . '</strong> berhasil dihapus');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(EmployeeDeduction $deduction)
    {
        $deduction->restore();

        return redirect()->back()->with('success', 'Pengajuan reimbursement <strong>' . $deduction->employee->user->name . '</strong> berhasil dipulihkan');
    }
}
