<?php

namespace Modules\Portal\Http\Controllers\Salary;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Core\Enums\PayrollSettingEnum;
use Modules\Core\Enums\SalaryUnitEnum;
use Modules\Core\Models\CompanyPayrollSetting;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Models\EmployeeSalary;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Notifications\Salary\Slip\SendToMailNotification;

class SlipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $employee = $request->user()->employee;

        $salaries = $employee->salaries()
            ->whereNotNull('released_at')
            ->search($request->get('search'))
            ->latest()
            ->paginate($request->get('limit', 10));

        $salaries_count = $employee->salaries()->count();

        return view('portal::salary.slips.index', compact('employee', 'salaries', 'salaries_count'));
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeSalary $salary, Request $request)
    {
        if (!$request->user()->is($salary->employee->user)) {
            return abort(404);
        }

        $document = $salary->firstOrCreateDocument(
            $title = 'Slip ' . $salary->name . ' - ' . $salary->created_at->getTimestamp(),
            $path = 'employee/salaries/' . Str::random(36) . '.pdf'
        );

        $attachments = [];
        if ($recap = $salary->employee->dataRecapitulations()->whereType(DataRecapitulationTypeEnum::OUTWORK)->whereStrictPeriodIn($salary->start_at, $salary->end_at)->first()) {
            $attachments['outworks'] = $salary->employee->outworks()->with('category')->whereIn('id', array_column($recap->result->outworks, 'id'))->get()->map(fn ($o) => [
                'id' => $o->id,
                'category' => $o->category->name,
                'name' => $o->name,
                'description' => $o->description,
                'dates' => $o->dates,
                'amount' => collect($recap->result->outworks)->firstWhere('id', $o->id)->dates
            ]);
        }

        if ($recap = $salary->employee->dataRecapitulations()->whereType(DataRecapitulationTypeEnum::REIMBURSEMENT)->whereStrictPeriodIn($salary->start_at, $salary->end_at)->first()) {
            $attachments['reimbursements'] = $salary->employee->reimbursements()->with('category')->whereIn('id', array_column($recap->result->reimbursements, 'id'))->get()->map(fn ($r) => [
                'id' => $r->id,
                'category' => $r->category->name,
                'name' => $r->name,
                'description' => $r->description,
                'submitted_at' => $r->created_at,
                'amount' => collect($recap->result->reimbursements)->firstWhere('id', $r->id)->total
            ]);
        }

        // Overtimes
        if ($recap = $salary->employee->dataRecapitulations()->whereType(DataRecapitulationTypeEnum::OVERTIME)->whereStrictPeriodIn($salary->start_at, $salary->end_at)->first()) {
            $attachments['overtimes'] = $salary->employee->overtimes()->whereIn('id', array_column($recap->result->overtimes, 'id'))->get()->map(fn ($ov) => [
                'id' => $ov->id,
                'name' => $ov->name,
                'description' => $ov->description,
                'dates' => $ov->dates,
                'amount' => collect($recap->result->overtimes)->firstWhere('id', $ov->id)->dates
            ]);
        }

        $units = SalaryUnitEnum::cases();

        $config = CompanyPayrollSetting::firstWhere('az', PayrollSettingEnum::APPROVABLE);

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

        Storage::disk('docs')->put($document->path, PDF::setPaper('A5', 'landscape')->loadView('hrms::payroll.calculations.show', compact('salary', 'document', 'title', 'units', 'attachments'))->output());

        return $document->show();
    }

    /**
     * Show the form for editing the current resource.
     */
    public function edit(EmployeeSalary $salary, Request $request)
    {
        if (!$request->user()->is($salary->employee->user)) {
            return abort(404);
        }


        $attachments = [];
        if ($recap = $salary->employee->dataRecapitulations()->whereType(DataRecapitulationTypeEnum::OUTWORK)->whereStrictPeriodIn($salary->start_at, $salary->end_at)->first()) {
            $attachments['outworks'] = $salary->employee->outworks()->with('category')->whereIn('id', array_column($recap->result->outworks, 'id'))->get()->map(fn ($o) => [
                'id' => $o->id,
                'category' => $o->category->name,
                'name' => $o->name,
                'description' => $o->description,
                'dates' => $o->dates,
                'amount' => collect($recap->result->outworks)->firstWhere('id', $o->id)->dates
            ]);
        }

        if ($recap = $salary->employee->dataRecapitulations()->whereType(DataRecapitulationTypeEnum::REIMBURSEMENT)->whereStrictPeriodIn($salary->start_at, $salary->end_at)->first()) {
            $attachments['reimbursements'] = $salary->employee->reimbursements()->with('category')->whereIn('id', array_column($recap->result->reimbursements, 'id'))->get()->map(fn ($r) => [
                'id' => $r->id,
                'category' => $r->category->name,
                'name' => $r->name,
                'description' => $r->description,
                'submitted_at' => $r->created_at,
                'amount' => collect($recap->result->reimbursements)->firstWhere('id', $r->id)->total
            ]);
        }

        // Overtimes
        if ($recap = $salary->employee->dataRecapitulations()->whereType(DataRecapitulationTypeEnum::OVERTIME)->whereStrictPeriodIn($salary->start_at, $salary->end_at)->first()) {
            $attachments['overtimes'] = $salary->employee->overtimes()->whereIn('id', array_column($recap->result->overtimes, 'id'))->get()->map(fn ($ov) => [
                'id' => $ov->id,
                'name' => $ov->name,
                'description' => $ov->description,
                'dates' => $ov->dates,
                'amount' => collect($recap->result->overtimes)->firstWhere('id', $ov->id)->dates
            ]);
        }

        return view('portal::salary.slips.edit', [
            ...compact('salary', 'attachments'),
            'units' => SalaryUnitEnum::cases(),
            'employee' => $salary->employee->load('position')
        ]);
    }

    /**
     * Update validations.
     */
    public function update(EmployeeSalary $salary, Request $request)
    {
        if (!$request->user()->is($salary->employee->user)) {
            return abort(404);
        }

        $salary->update([
            'accepted_at' => $request->has('accepted') ? now() : null
        ]);


        $salary->employee->user->notify(new SendToMailNotification($salary));

        return redirect()->next()->with('success', 'Terima kasih telah menandatangani gaji <strong>' . $salary->name . '</strong> untuk <strong>' . $request->user()->name . '</strong>!');
    }
}
