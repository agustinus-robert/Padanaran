<?php

namespace Modules\HRMS\Http\Controllers\Summary;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Core\Models\CompanyDepartment;
use Modules\Core\Models\CompanySalaryTemplate;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Http\Requests\Summary\PostYear\StoreRequest;
use Modules\HRMS\Models\EmployeeDataRecapitulation;

class PostYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeDataRecapitulation::class);

        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        return view('hrms::summary.postyears.index', [
            ...compact('start_at', 'end_at'),
            'departments' => CompanyDepartment::visible()->with('positions')->get(),
            'employees' => Employee::with('user')
                ->has('contract')
                ->whenWithTrashed($request->get('trashed'))
                ->with(['dataRecapitulations' => fn ($recap) => $recap->where('type', DataRecapitulationTypeEnum::G13)->where('start_at', $start_at->format('Y-m-d'))->where('end_at', $end_at->format('Y-m-d'))])
                ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
                ->search($request->get('search'))
                ->paginate($request->get('limit', 10)),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Employee $employee, Request $request)
    {
        $this->authorize('store', EmployeeDataRecapitulation::class);

        $start_at = Carbon::parse($request->get('start_at', now()));
        $end_at   = Carbon::parse($request->get('end_at', now()));
        $period   = $employee->joined_at <= $start_at->clone()->startOfYear() ? $start_at->clone()->startOfYear() : $employee->joined_at->clone();
        $periods  = CarbonPeriod::create($period->format('Y-m-d'), '1 month', $end_at->clone()->endOfYear()->format('Y-m-d'));
        $cmpDefaultTemplate = CompanySalaryTemplate::whereRaw("meta::jsonb ->> 'default' = 'true'")->first();
        $defaultTemplate = $cmpDefaultTemplate?->components ?? [];
        $postyearComponents = CompanySalaryTemplate::whereRaw("meta::jsonb ->> 'postyear' = 'true'")
            ->first()
            ?->components ?? null;

        if (is_null($postyearComponents)) {
            return redirect()->back()->with('danger', 'Belum ada template gaji ke 13, silakan buat template terlebih dahulu!');
        }

        // get reference from slip template
        foreach (collect($postyearComponents) as $k => $v) {
            $_val[$k] = collect($defaultTemplate)->where('name', $v->name)->first();
        }
        $val_id = collect($_val)->unique()->pluck('component_id');

        $salaryTemplates = $employee->salaryTemplates()->with(['items' => fn ($cmpn) => $cmpn->with(['component'])->whereIn('component_id', $val_id)])->where('cmp_template_id', $cmpDefaultTemplate->id)->where(fn ($y) => $y->whereYear('start_at', date('Y'))->orWhereYear('end_at', date('Y')))->get()->filter(fn ($f) => $f->items->count() > 0);

        return view('hrms::summary.postyears.create', [
            ...compact('start_at', 'end_at', 'val_id'),
            'periods' => $periods,
            'employee' => $employee->load(['contract', 'user', 'position.position']),
            'salaryTemplates' => $salaryTemplates
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Employee $employee, StoreRequest $request)
    {
        $summary = array_merge(
            $request->transformed()->toArray(),
            [
                'type' => DataRecapitulationTypeEnum::G13,
                'empl_id' => $employee->id
            ]
        );

        if (EmployeeDataRecapitulation::updateOrCreate(Arr::only($summary, ['start_at', 'end_at', 'empl_id', 'type']), $summary)) {
            $request->user()->log('melakukan rekapitulasi Gaji ke-13 <strong>' . $employee->user->name . '</strong> untuk cut off tanggal <strong>' . $request->input('start_at') . ' s.d. ' . $request->input('end_at') . '</strong>', Employee::class, $employee->id);
            return redirect()->next()->with('success', 'Rekapitulasi Gaji ke-13 <strong>' . $employee->user->name . '</strong> untuk cut off tanggal <strong>' . $request->input('start_at') . ' s.d. ' . $request->input('end_at') . '</strong> berhasil disimpan');
        }
        return redirect()->fail();
    }
}
