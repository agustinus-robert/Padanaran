<?php

namespace Modules\HRMS\Http\Controllers\Summary;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeVacationQuota;
use Modules\HRMS\Http\Requests\Summary\Vacation\StoreRequest;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\EmployeeDataRecapitulation;
use Modules\HRMS\Models\EmployeeVacation;
use Modules\HRMS\Repositories\EmployeeVacationCompensationRepository;

class VacationController extends Controller
{
    use EmployeeVacationCompensationRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeVacationQuota::class);
        $start_at   = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at     = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');
        $year       = $end_at->format('Y');
        $employees  = Employee::with([
            'user.meta', 'contract',
            'dataRecapitulations' => fn ($dt) => $dt->where('type', DataRecapitulationTypeEnum::CASHABLE_VACATION)->where('start_at', $start_at->format('Y-m-d'))->where('end_at', $end_at->format('Y-m-d')),
            'vacationQuotas' => fn ($quota) => $quota->with('category', 'vacations')->whereIn('ctg_id', [1, 2, 3])->active()
        ])
            ->has('contract')
            ->search($request->get('search'))
            ->whenTrashed($request->get('trash'))
            ->paginate($request->get('limit', 10));

        return view('hrms::summary.vacations.index', compact('year', 'employees', 'start_at', 'end_at'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', EmployeeVacationQuota::class);

        $start_at   = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at     = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');
        $year       = $request->get('year', $end_at->format('Y'));
        $employee   = Employee::with(['vacationQuotas' => fn ($quota) => $quota->with('vacations')->has('employee.contract')->whenInYear($year)])->find($request->get('employee'));
        $quota      = EmployeeVacationQuota::with('vacations', 'category')->findorfail($request->get('quota'));
        $extend_at  = now()->copy()->copy()->endOfYear()->addMonth('6')->subDay(1)->format('Y-m-d 23:59:59');

        return view('hrms::summary.vacations.create', compact('employee', 'quota', 'start_at', 'end_at', 'extend_at'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $input = $request->transformed()->toArray();

        $empl = Employee::findorfail($request->input('employee'));

        if ($cashable = $this->storeEmployeeVacationCompensation($empl, $input)) {
            if ($request->input('extend') > 0) {
                $this->extendQuota($request->input('quota_id'), $request->input('extend_at'));
            }
            return redirect()->next()->with('success', 'Daftar distribusi cuti karyawan atas nama <strong>' . $cashable->employee->user->name . '</strong> telah berhasil ditambahkan.');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeVacation $vacation, EmployeeDataRecapitulation $recap, Request $request)
    {
        $this->authorize('destroy', $recap);

        if ($data = $this->destroyEmployeeVacationCompensation($vacation, $recap)) {
            return redirect()->next()->with('success', 'kompensasi cuti karyawan atas nama <strong>' . $data->employee->user->name . '</strong> telah berhasil dihapus.');
        }
        return redirect()->fail();
    }
}
