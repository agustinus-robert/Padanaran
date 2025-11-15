<?php

namespace Modules\Core\Http\Controllers\Company\Salary;

use Illuminate\Http\Request;
use Modules\Core\Http\Requests\Company\Salary\Approval\StoreRequest;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\CompanyPayrollSetting;
use Modules\Core\Models\CompanySalaryTemplate;
use Modules\Core\Repositories\CompanySalarySlipSettingRepository;
use Modules\HRMS\Models\Employee;

class ApprovalController extends Controller
{
    use CompanySalarySlipSettingRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', CompanyPayrollSetting::class);

        return view('core::company.salaries.approvals.index', [
            'slips' => CompanySalaryTemplate::all(),
            'settings' => CompanyPayrollSetting::paginate($request->get('limit', 10)),
            'employees' => Employee::with('user', 'contract.position.position')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($this->storeApprovalSalarySlip($request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Pengaturan persetujuan gaji telah berhasil dibuat.');
        }
        return redirect()->fail();
    }


    /**
     * Update the specified resource in storage.
     */
    public function destroy(CompanyPayrollSetting $approval)
    {
        if ($this->destroyApprovalSalarySlip($approval)) {
            return redirect()->next()->with('success', 'Pengaturan persetujuan gaji telah berhasil dihapus.');
        }
        return redirect()->fail();
    }
}
