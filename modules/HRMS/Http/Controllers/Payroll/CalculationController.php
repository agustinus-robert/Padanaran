<?php

namespace Modules\HRMS\Http\Controllers\Payroll;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Account\Enums\ReligionEnum;
use Modules\Core\Enums\PayrollSettingEnum;
use Modules\Core\Enums\SalaryUnitEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\Core\Models\CompanyPayrollSetting;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeSalary;
use Modules\Core\Models\CompanySalarySlipComponent;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Http\Requests\Payroll\Calculation\StoreRequest;
use Modules\HRMS\Http\Controllers\Controller;

class CalculationController extends Controller
{
    /**
     * Display all resource
     * */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeSalary::class);

        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');
        $religions = ReligionEnum::cases();

        return view('hrms::payroll.calculations.index', [
            ...compact('start_at', 'end_at', 'religions'),
            'departments' => CompanyDepartment::visible()->with('positions')->get(),
            'employees' => Employee::with(['salaries' => fn($salary) => $salary->whereDate('start_at', $start_at->format('Y-m-d'))->whereDate('end_at', $end_at->format('Y-m-d')), 'user', 'position.position'])
                ->whereHas('user', fn($q) => $q->whereMetaIn('profile_religion', $request->get('religions', array_map(fn($religion) => $religion->value, $religions))))
                ->whenWithTrashed($request->get('trashed'))
                ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
                ->isActive()
                ->search($request->get('search'))
                ->paginate($request->get('limit', 10)),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', EmployeeSalary::class);

        $start_at = Carbon::parse($request->get('start_at') . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at') . ' 23:59:59');

        $components = CompanySalarySlipComponent::with('category.slip')->get();
        $employee = Employee::has('contract')->with('salaryTemplates.items.component')->find($request->get('employee'));

        $components->each(function ($component) use ($employee, $request, $start_at, $end_at) {
            if ($items = $employee->salaryTemplates->firstWhere('id', $request->get('template'))?->items) {
                $amount = 0;
                $multiplier = ($component->meta?->as_multiplier ?? false) ? ($items->firstWhere('component_id', $component->id)?->amount ?? 0) : 1;
                switch ($component->meta?->algorithm?->method ?? null) {
                    case 'MODEL':
                        foreach (($component->meta->algorithm?->models ?? []) as $model => $x) {
                            $query = new $model;
                            foreach ($x->conditions as $clauses) {
                                foreach ($clauses->p as $i => $clause) {
                                    $clauses->p[$i] = match ($clause) {
                                        '%CURRENT_EMPL_ID%' => $employee->id,
                                        '%START_AT%' => $start_at->format('Y-m-d'),
                                        '%END_AT%' => $end_at->format('Y-m-d'),
                                        '%COMPONENT_ID%' => $component->id,
                                        default => $clause
                                    };
                                };
                                $query = $query->{$clauses->f}(...$clauses->p);
                            }
                            if (isset($x->after)) {
                                foreach ($query->get() as $recap) {
                                    $amount += match ($x->after) {
                                        'multiply_by_self_overdays' => $query->{$x->action}($x->action_column) * $employee->getOverdaysSalary(),
                                        default => $query->{$x->action}($x->action_column)
                                    };
                                }
                            } else {
                                $amount += $query->{$x->action}($x->action_column);
                            };
                        }
                        break;
                    default:
                        $amount = $items->firstWhere('component_id', $component->id)->amount ?? 0;
                        break;
                }
                $component->multiplier = $multiplier;
                $component->default_amount = $amount;
            };
            return $component;
        });

        return view('hrms::payroll.calculations.create', compact('start_at', 'end_at', 'components', 'employee'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('store', EmployeeSalary::class);

        $employee = Employee::find($request->input('employee'));

        if ($employee->salaries()->updateOrCreate($request->transformed()->only('start_at', 'end_at'), $request->transformed()->toArray())) {
            $request->user()->log('melakukan payroll <strong>' . $employee->user->name . '</strong> untuk cut off tanggal <strong>' . $request->input('start_at') . ' &mdash; ' . $request->input('end_at') . '</strong>', Employee::class, $employee->id);

            return redirect()->next()->with('success', 'Payroll <strong>' . $employee->user->name . '</strong> untuk cut off tanggal <strong>' . $request->input('start_at') . ' &mdash; ' . $request->input('end_at') . '</strong> berhasil disimpan');
        }

        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeSalary $salary)
    {
        $this->authorize('show', $salary);

        $document = $salary->firstOrCreateDocument(
            $title = 'Slip ' . $salary->name . ' - ' . $salary->created_at->getTimestamp(),
            $path = 'employee/salaries/' . Str::random(36) . '.pdf'
        );

        $attachments = [];
        if ($recap = $salary->employee->dataRecapitulations()->whereType(DataRecapitulationTypeEnum::OUTWORK)->whereStrictPeriodIn($salary->start_at, $salary->end_at)->first()) {
            $attachments['outworks'] = $salary->employee->outworks()->with('category')->whereIn('id', array_column($recap->result->outworks, 'id'))->get()->map(fn($o) => [
                'id' => $o->id,
                'category' => $o->category->name,
                'name' => $o->name,
                'description' => $o->description,
                'dates' => $o->dates,
                'amount' => collect($recap->result->outworks)->firstWhere('id', $o->id)->dates
            ]);
        }

        // Overtimes
        if ($recap = $salary->employee->dataRecapitulations()->whereType(DataRecapitulationTypeEnum::OVERTIME)->whereStrictPeriodIn($salary->start_at, $salary->end_at)->first()) {
            $attachments['overtimes'] = $salary->employee->overtimes()->whereIn('id', array_column($recap->result->overtimes, 'id'))->get()->map(fn($ov) => [
                'id' => $ov->id,
                'name' => $ov->name,
                'description' => $ov->description,
                'dates' => $ov->dates,
                'amount' => collect($recap->result->overtimes)->firstWhere('id', $ov->id)->dates
            ]);
        }

        if ($recap = $salary->employee->dataRecapitulations()->whereType(DataRecapitulationTypeEnum::COORD)->whereStrictPeriodIn($salary->start_at, $salary->end_at)->first()) {
            $attachments['coordinator_student'] = (array) $recap['result'];
            unset($attachments['coordinator_student']['grand_total']);
        }

        if ($recap = $salary->employee->dataRecapitulations()->whereType(DataRecapitulationTypeEnum::DEDUCTION)->whereStrictPeriodIn($salary->start_at, $salary->end_at)->first()) {
            $attachments['deduction'] = (array) $recap['result'];
            unset($attachments['deduction']['total']);
        }

        $units = SalaryUnitEnum::cases();

        $config = CompanyPayrollSetting::firstWhere('az', PayrollSettingEnum::APPROVABLE);

        if (!$config) {
            return redirect()->back()->with('danger', 'Kamu belum membuat konfigurasi persetujuan gaji, buat dulu ya!');
        }

        $dataSign = array_merge(collect($config->meta?->approvable_step)->toArray(), ['%SELECTED_EMPLOYEE_USER_ID%']);

        $signs = array_map(
            function ($sign) use ($salary) {
                if ($sign == '%SELECTED_EMPLOYEE_USER_ID%') {
                    return $salary->employee->user_id;
                } elseif (isset($sign->model)) {
                    $model = new $sign->model();
                    $model = $model->{$sign->methods}($sign->prop);
                    return data_get($model, $sign->get);
                }
            },
            $dataSign
        );

        $document->sign($signs, true);

        Storage::disk('docs')->put($document->path, PDF::setPaper('A5', 'landscape')->loadView('finance::payroll.calculations.show', compact('salary', 'document', 'title', 'units', 'attachments'))->output());

        return $document->show();
    }
}
