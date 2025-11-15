<?php

namespace Modules\HRMS\Http\Controllers\Benefit\Insurance;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyInsurance;
use Modules\Core\Models\CompanyInsurancePrice;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeInsurance;
use Modules\HRMS\Repositories\EmployeeInsuranceRepository;
use Modules\HRMS\Http\Requests\Benefit\Insurance\StoreRequest;
use Modules\HRMS\Http\Controllers\Controller;

class RegistrationController extends Controller
{
    use EmployeeInsuranceRepository;

    /**
     * Display all resource
     * */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeInsurance::class);

        return view('hrms::benefit.insurances.index', [
            'required_salary' => ($required_salary = CompanyInsurancePrice::pluck('price_factor_additional')->flatten()->unique()->filter()),
            'employees' => Employee::with(['user', 'position.position', 'insurances.price.insurance', 'salaryTemplate' => fn($item) => $item->whereHas('items.component', fn($component) => $component->whereIn('kd', $required_salary))])
                ->search($request->get('search'))
                ->paginate($request->get('limit', 10)),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', EmployeeInsurance::class);

        $employee = Employee::with(['salaryTemplate' => fn($template) => $template->hasPrimarySalary()->with('items.component')])->find($request->get('employee'));

        $refSal = setting('cmp_insurance_max_salary') ?? (config('modules.hrms.features.benefit.insurance.maxSalary') ?? 0);

        return view('hrms::benefit.insurances.create', [
            'employee' => $employee,
            'insurances' => CompanyInsurance::with('prices.insurance')->get(),
            'max_salary' => $employee->getMainSalary() > $refSal ? $refSal : $employee->getMainSalary()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('store', EmployeeInsurance::class);

        $employee = Employee::find($request->input('employee'));

        $insurances = [];
        foreach ($request->transformed()->input('insurances') as $insurance) {
            $insurances[] = $this->storeEmployeeInsurance($employee, $insurance);
        }

        return redirect()->next()->with([
            'success' => 'Data asuransi ' . count(array_filter($insurances)) . ' dari total ' . count($request->transformed()->input('insurances')) . ' telah berhasil dibuat.',
            'danger' => count($errors = array_filter($insurances, fn($log) => $log == false)) ? 'Terjadi kesalahan ketika memproses data, silakan hubungi administrator, terima kasih. [ID Gagal: ' . implode(',', array_keys($errors)) . ']' : null
        ]);
    }

    public function destroy(EmployeeInsurance $insurance)
    {
        $this->authorize('destroy', $insurance);

        if ($data = $this->destroyInsurance($insurance)) {
            return redirect()->next()->with('success', 'Daftar asuransi <strong>' . $data->price->insurance->name . '</strong> karyawan atas nama <strong>' . $data->employee->user->name . '</strong> telah berhasil dihapus.');
        }
        return redirect()->fail();
    }

    public function reset(Employee $employee)
    {
        $this->authorize('destroy', $employee);

        if ($employee->insurances()->delete()) {
            return redirect()->next()->with('success', 'Daftar asuransi karyawan atas nama <strong>' . $employee->user->name . '</strong> telah berhasil dikosongkan.');
        }
        return redirect()->fail();
    }

    public function savemaxsalary(Request $request)
    {
        $this->authorize('store', EmployeeInsurance::class);

        setting_set('cmp_insurance_max_salary', $request->input('max_salary'));

        return redirect()->next()->with('success', 'Setting maksimal gaji BPJS Pensiun sebesar <strong>' . $request->input('max_salary') . '</strong> telah berhasil disimpan.');
    }
}
