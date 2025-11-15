<?php

namespace Modules\Finance\Http\Controllers\Summary;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Account\Enums\ReligionEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\Core\Models\CompanyMoment;
use Modules\Core\Models\CompanySalaryTemplate;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Http\Requests\Summary\FeastDay\StoreRequest;
use Modules\HRMS\Models\EmployeeDataRecapitulation;

class FeastDayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeDataRecapitulation::class);

        $cutoff_at = Carbon::parse($request->get('cutoff_at', now()));
        $religions = ReligionEnum::cases();

        return view('finance::summary.feastdays.index', [
            ...compact('cutoff_at', 'religions'),
            'departments' => CompanyDepartment::where('grade_id', userGrades())->visible()->with('positions')->get(),
            'employees' => Employee::with('user')
                ->where('grade_id', userGrades())
                ->whereHas('user', fn($q) => $q->whereMetaIn('profile_religion', $request->get('religions', array_map(fn($religion) => $religion->value, $religions))))
                ->has('contract')
                ->whenWithTrashed($request->get('trashed'))
                ->with(['dataRecapitulations' => fn($recap) => $recap->where('type', DataRecapitulationTypeEnum::THR)->where('start_at', $cutoff_at->format('Y-m-d'))->where('end_at', $cutoff_at->format('Y-m-d'))])
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

        $religion  = $employee->user->getMeta('profile_religion');
        $cutoff_at = Carbon::parse($request->get('cutoff_at', now()));
        $period    = $cutoff_at->clone();
        $moment    = CompanyMoment::whereYear('date', (date('Y') - 1))->get()->filter(fn($value) => $value->meta != null && in_array($religion, $value->meta->religion));
        $start_at  = $moment->count() > 0 && $employee->joined_at->lt(Carbon::parse($moment->first()->date)) ? $period->startOfYear()->format('Y-m-d') : $employee->joined_at->format('Y-m-d');
        $periods   = CarbonPeriod::create($start_at, '1 month', $period->endOfYear()->format('Y-m-d'));
        $cmpDefaultTemplate = CompanySalaryTemplate::where('grade_id', userGrades())->whereJsonContains('meta->default', true)->first();
        $defaultTemplate = $cmpDefaultTemplate->components;
        $feastdayComponents = CompanySalaryTemplate::where('grade_id', userGrades())->whereJsonContains('meta->feastday', true)->first()->components ?? null;

        if (is_null($feastdayComponents)) {
            return redirect()->back()->with('danger', 'Belum ada template THR, silakan buat template terlebih dahulu!');
        }

        // get reference from slip template
        foreach (collect($feastdayComponents) as $k => $v) {
            $_val[$k] = collect($defaultTemplate)->where('name', $v->name)->first();
        }
        $val_id = collect($_val)->unique()->pluck('component_id');

        $salaryTemplates = $employee->salaryTemplates()->with(['items' => fn($cmpn) => $cmpn->with(['component'])->whereIn('component_id', $val_id)])->where('cmp_template_id', $cmpDefaultTemplate->id)->where(fn($y) => $y->whereYear('start_at', date('Y'))->orWhereYear('end_at', date('Y')))->get()->filter(fn($f) => $f->items->count() > 0);

        return view('finance::summary.finance.create', [
            ...compact('cutoff_at'),
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
                'type' => DataRecapitulationTypeEnum::THR,
                'empl_id' => $employee->id
            ]
        );

        if (EmployeeDataRecapitulation::updateOrCreate(Arr::only($summary, ['start_at', 'end_at', 'empl_id', 'type']), $summary)) {
            $request->user()->log('melakukan rekapitulasi THR <strong>' . $employee->user->name . '</strong> untuk cut off tanggal <strong>' . $request->input('end_at') . '</strong>', Employee::class, $employee->id);
            return redirect()->next()->with('success', 'Rekapitulasi THR <strong>' . $employee->user->name . '</strong> untuk cut off tanggal <strong>' . $request->input('end_at') . '</strong> berhasil disimpan');
        }
        return redirect()->fail();
    }
}
