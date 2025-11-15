<?php

namespace Modules\HRMS\Http\Controllers\Payroll;

use App\Models\Setting;
use Illuminate\Http\Request;
use Modules\Core\Models\CompanySalarySlipComponent;
use Modules\HRMS\Http\Requests\Payroll\Config\StoreRequest;
use Modules\HRMS\Http\Requests\Payroll\Config\UpdateRequest;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\EmployeeSalaryTemplate;
use Modules\Reference\Models\PayrolReference;

class ConfigController extends Controller
{
    /**
     * Display all resource
     * */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeSalaryTemplate::class);

        $settings = PayrolReference::whereJsonContains('meta->config', '1')->paginate($request->get('limit', 10));

        return view('hrms::payroll.configs.index', [
            'settings' => $settings
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', EmployeeSalaryTemplate::class);

        $default_component = CompanySalarySlipComponent::whereJsonContains('meta->default_component', true)->first();
        $components = CompanySalarySlipComponent::with('slip')->where('operate', '!=', 0)->get();

        return view('hrms::payroll.configs.create', [
            'default' => $default_component,
            'components' => $components,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('store', EmployeeSalaryTemplate::class);

        $data = $request->transformed()->toArray();

        $data = PayrolReference::create($data);

        return redirect()->next()->with(['success' => 'Pengaturan telah berhasil dibuat.']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(PayrolReference $config, Request $request)
    {
        $this->authorize('access', EmployeeSalaryTemplate::class);
        $default_component = CompanySalarySlipComponent::whereJsonContains('meta->default_component', true)->first();
        $components = CompanySalarySlipComponent::with('slip')->where('operate', '!=', 0)->get();

        return view('hrms::payroll.configs.edit', [
            'default' => $default_component,
            'components' => $components,
            'config' => $config,
        ]);
    }

    /**
     * Update a resource in storage.
     */
    public function update(UpdateRequest $request, PayrolReference $config)
    {
        $this->authorize('store', EmployeeSalaryTemplate::class);

        $data = $request->transformed()->toArray();

        $config->fill($data);

        if ($config->save()) {

            return redirect()->next()->with(['success' => 'Pengaturan telah berhasil diperbarui.']);
        }
        return redirect()->fail();
    }

    public function destroy(PayrolReference $config)
    {
        if ($template = $config->delete()) {
            return redirect()->next()->with('success', 'Data pengaturan telah berhasil dihapus.');
        }
        return redirect()->fail();
    }
}
