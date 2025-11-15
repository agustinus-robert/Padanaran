<?php

namespace Modules\HRMS\Http\Controllers\Service\Vacation;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyVacationCategory;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeVacationQuota;
use Modules\HRMS\Repositories\EmployeeVacationQuotaRepository;
use Modules\HRMS\Http\Requests\Service\Vacation\Quota\StoreRequest;
use Modules\HRMS\Http\Requests\Service\Vacation\Quota\UpdateRequest;
use Modules\HRMS\Http\Controllers\Controller;

class QuotaController extends Controller
{
    use EmployeeVacationQuotaRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeVacationQuota::class);

        $year = $request->get('year', date('Y'));

        $employees = Employee::where('grade_id', userGrades())->with(['user.meta', 'contract', 'vacationQuotas' => fn($quota) => $quota->with('vacations')->whenInYear($year)])
            ->search($request->get('search'))
            ->whenTrashed($request->get('trash'))
            ->paginate($request->get('limit', 10));

        return view('hrms::service.vacation.quotas.index', compact('year', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', EmployeeVacationQuota::class);

        $year = $request->get('year', date('Y'));
        $categories = CompanyVacationCategory::where('grade_id', userGrades())->get();
        $employee = Employee::where('grade_id', userGrades())->with(['vacationQuotas' => fn($quota) => $quota->with('vacations')->has('employee.contract')->whenInYear($year)])->find($request->get('employee'));
        $quotanow = getQuotaNow($employee, $year);

        return view('hrms::service.vacation.quotas.create', compact('categories', 'employee', 'quotanow'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $input = $request->transformed()->toArray();

        if ($request->input('as_template')) {
            setting_set('cmp_services_vacation_quotas', $input['quotas']);
        }

        if ($employee = $this->storeEmployeeVacationQuota($input)) {
            return redirect()->next()->with('success', 'Daftar distribusi cuti karyawan atas nama <strong>' . $employee->user->name . '</strong> telah berhasil ditambahkan.');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeVacationQuota $quota, Request $request)
    {
        $this->authorize('destroy', $quota);

        if ($quota->vacations->count()) {
            return redirect()->fail('Maaf, Anda tidak dapat menghapus kategori ini karena telah dipakai');
        }

        if ($quota = $this->destroyEmployeeVacationQuota($quota)) {
            return redirect()->next()->with('success', 'Daftar distribusi cuti <strong>' . $quota->category->name . '</strong> karyawan atas nama <strong>' . $quota->employee->user->name . '</strong> telah berhasil dihapus.');
        }
        return redirect()->fail();
    }

    /**
     * create batch quota.
     */
    public function batch_create(Request $request)
    {
        $this->authorize('store', EmployeeVacationQuota::class);
        if ($this->generateVacationThisYear($request->get('year'))) {
            return redirect()->next()->with('success', 'Daftar distribusi cuti karyawan telah berhasil disimpan.');
        }
        return redirect()->fail();
    }
}
