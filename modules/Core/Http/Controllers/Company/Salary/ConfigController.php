<?php

namespace Modules\Core\Http\Controllers\Company\Salary;

use Illuminate\Http\Request;
use Modules\Core\Enums\PayrollSettingEnum;
use Modules\Core\Http\Requests\Company\Salary\Config\StoreRequest;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\CompanyPayrollSetting;
use Modules\Core\Models\CompanySalarySlipComponent;
use Modules\Core\Models\CompanySalaryTemplate;
use Modules\Core\Repositories\CompanySalarySlipSettingRepository;
use Modules\HRMS\Models\Employee;

class ConfigController extends Controller
{
    use CompanySalarySlipSettingRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', CompanyPayrollSetting::class);

        return view('core::company.salaries.configs.index', [
            'slips' => CompanySalaryTemplate::where('grade_id', userGrades())->get(),
            'settings' => CompanyPayrollSetting::whenTrashed($request->get('trash'))->where('grade_id', userGrades())->paginate($request->get('limit', 10)),
            'setting_count' => CompanyPayrollSetting::where('grade_id', userGrades())->count(),
            'employees' => Employee::with('user', 'contract.position.position')->where('grade_id', userGrades())->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', CompanyPayrollSetting::class);

        $default_component = CompanySalarySlipComponent::whereJsonContains('meta->default_component', true)->where('grade_id', userGrades())->first();
        $components = CompanySalarySlipComponent::with('slip')->where('operate', '!=', 0)->where('grade_id', userGrades())->get();
        $types = PayrollSettingEnum::cases();
        $active = PayrollSettingEnum::tryFrom($request->get('active_id')) ? PayrollSettingEnum::tryFrom($request->get('active_id'))->template() : null;
        $disabled = CompanyPayrollSetting::where('grade_id', userGrades())->whereAz(PayrollSettingEnum::APPROVABLE)->first()?->az->value;

        return view('core::company.salaries.configs.create', [
            'default' => $default_component,
            'components' => $components,
            'types' => $types,
            'active' => $active,
            'disabled' => $disabled,
            'employees' => Employee::with('user', 'contract.position.position')->where('grade_id', userGrades())->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($this->storePayrollConfig($request->transformed()->toArray(), $request->user())) {
            return redirect()->next()->with('success', 'Pengaturan persetujuan gaji telah berhasil dibuat.');
        }
        return redirect()->fail();
    }


    /**
     * Update the specified resource in storage.
     */
    public function destroy(CompanyPayrollSetting $config, Request $request)
    {
        if ($this->destroyPayrollConfig($config, $request->user())) {
            return redirect()->next()->with('success', 'Pengaturan persetujuan gaji telah berhasil dihapus.');
        }
        return redirect()->fail();
    }
}
