<?php

namespace Modules\HRMS\Http\Controllers\Payroll;

use App\Models\Setting;
use Illuminate\Http\Request;
use Modules\Account\Enums\ReligionEnum;
use Modules\Core\Enums\PayrollSettingEnum;
use Modules\Core\Models\CompanyPayrollSetting;
use Modules\HRMS\Models\Employee;
use Modules\Core\Models\CompanySalarySlip;
use Modules\Core\Models\CompanySalarySlipComponent;
use Modules\Core\Models\CompanySalaryTemplate;
use Modules\HRMS\Repositories\EmployeeSalaryTemplateRepository;
use Modules\HRMS\Http\Requests\Payroll\Template\StoreRequest;
use Modules\HRMS\Http\Requests\Payroll\Template\UpdateRequest;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\EmployeeSalaryTemplate;
use Modules\Reference\Models\PayrolReference;

class TemplateController extends Controller
{
    use EmployeeSalaryTemplateRepository;

    /**
     * Display all resource
     * */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeSalaryTemplate::class);

        $year = $request->get('year', date('Y'));
        $religions = ReligionEnum::cases();

        return view('hrms::payroll.templates.index', [
            'employees' => Employee::with([
                'salaryTemplates' => fn ($template) => $template->withCount('items')->with('companyTemplate')->whenInYear($year),
                'user',
                'position.position'
            ])
                ->whereHas('user', fn ($q) => $q->whereMetaIn('profile_religion', $request->get('religions', array_map(fn ($religion) => $religion->value, $religions))))
                ->search($request->get('search'))->has('contract')->paginate($request->get('limit', 10)),
            'employees_count' => Employee::has('contract')->count(),
            'religions' => $religions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', EmployeeSalaryTemplate::class);

        $employee = Employee::with(['salaryTemplates' => fn ($t) => $t->with('items')->where('cmp_template_id', 1)->whereYear('start_at', now()->format('Y'))->whereYear('end_at', now()->format('Y')), 'contract'])->find($request->get('employee'));
        $activeTemplate = $employee->salaryTemplates->first() ?? null;
        $lastTemplate = $employee->lastSalaryTemplate ?? null;
        $default_component = CompanySalarySlipComponent::with('category.slip')->whereJsonContains('meta->default_component', true)->first();
        $settings = CompanyPayrollSetting::whereAz(PayrollSettingEnum::FORMULA)->get();
        $cmptid = $settings->pluck('meta.component');
        $secondarySlip = CompanySalarySlipComponent::with('category.slip')->whereIn('id', $cmptid)->get()->unique('category.id')->pluck('category')->first();

        return view('hrms::payroll.templates.create', [
            'employee'      => $employee,
            'slips'         => $slips = CompanySalarySlip::with('categories.components')->get(),
            'templates'     => CompanySalaryTemplate::get(),
            'components'    => CompanySalarySlipComponent::get(),
            'activeTemplate' => $activeTemplate ?? null,
            'lastTemplate'  => $lastTemplate ?? null,
            'templatename'  => $slips->firstwhere('az', 1)->name,
            'primarySlip'   => $default_component->category,
            'secondarySlip' => $secondarySlip,
            'defaultComponent' => $default_component,
            'cmptid'        => $cmptid,
            'settings'      => $settings,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('store', EmployeeSalaryTemplate::class);

        $input = $request->transformed()->toArray();
        if ($component = $this->storeEmployeeSalaryTemplate(Employee::find($request->input('employee')), $request->transformed()->toArray())) {
            return redirect()->next()->with(['success' => 'Data komponen gaji <strong>' . $component->employee->user->name . '</strong> telah berhasil dibuat.']);
        }
        return false;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(EmployeeSalaryTemplate $template, Request $request)
    {
        $this->authorize('access', EmployeeSalaryTemplate::class);
        $settings = CompanyPayrollSetting::whereAz(PayrollSettingEnum::FORMULA)->get();
        $cmptid = $settings->pluck('meta.component');
        $references = CompanySalarySlipComponent::with('category.slip')->whereIn('id', $cmptid)->get()->unique('category.id')->pluck('category');
        $default_component = CompanySalarySlipComponent::with('category.slip')->whereJsonContains('meta->default_component', true)->first();
        $secondarySlip = CompanySalarySlipComponent::with('category.slip')->whereIn('id', $cmptid)->get()->unique('category.id')->pluck('category')->first();

        return view('hrms::payroll.templates.edit', [
            'slips'      => $slips = CompanySalarySlip::with('categories.components')->get(),
            'components' => CompanySalarySlipComponent::get(),
            'template'   => $template->load('items'),
            'overtimeSalary' => $template->employee->getOvertimeSalary(),
            'primarySlip'   => $default_component->category,
            'secondarySlip' => $secondarySlip,
            'settings' => $settings,
            'defaultComponent' => $default_component,
            'cmptid' => $cmptid
        ]);
    }

    /**
     * Update a resource in storage.
     */
    public function update(EmployeeSalaryTemplate $template, UpdateRequest $request)
    {
        $this->authorize('store', EmployeeSalaryTemplate::class);

        if ($template = $this->updateEmployeeSalaryTemplate($template, $request->transformed()->toArray())) {
            return redirect()->next()->with(['success' => 'Data template gaji <strong>' . $template->employee->user->name . '</strong> telah berhasil dibuat.']);
        }
        return false;
    }

    public function destroy(EmployeeSalaryTemplate $template)
    {
        $this->authorize('destroy', $template);

        if ($template = $this->destroyEmployeeSalaryTemplate($template)) {
            return redirect()->next()->with('success', 'Data template gaji <strong>' . $template->employee->user->name . '-' . $template->name . '</strong> telah berhasil dihapus.');
        }
        return redirect()->fail();
    }
}
