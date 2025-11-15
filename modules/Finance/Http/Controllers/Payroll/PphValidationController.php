<?php

namespace Modules\Finance\Http\Controllers\Payroll;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanySalarySlipComponent;
use Modules\Core\Models\CompanySalaryTemplate;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\EmployeeSalary;
use Modules\Finance\Http\Requests\Payroll\PPh\UpdateRequest;

class PphValidationController extends Controller
{
    /**
     * Show the form for editing the current resource.
     */
    public function show(EmployeeSalary $salary, Request $request)
    {
        $components = CompanySalarySlipComponent::with('category.slip')->get();
        $cmpDefault = CompanySalaryTemplate::whereJsonContains('meta->default', true)->first()->id;
        $selectedTemplate = $salary->employee->salaryTemplates->where('cmp_template_id', $cmpDefault)->first()->load('items');
        $items = $salary->components->pluck('ctgs')->flatten(1)->pluck('i')->flatten(1);

        $components->each(function ($component) use ($salary, $items) {
            if (!$items) {
                return $component;
            }

            $amount = 0;
            $multiplier = 0;

            // Check if the component has default PPH
            if (optional($component->meta)->default_pph) {
                $multiplier = optional($component->meta)->as_multiplier
                    ? ($items->firstWhere('component_id', $component->id)->amount ?? 0)
                    : 1;

                switch ($component->meta->algorithm?->method ?? false) {
                    case 'MODEL':
                        foreach (($component->meta->algorithm?->models ?? []) as $model => $config) {
                            $query = new $model;
                            foreach ($config->conditions as $k => $clauses) {
                                foreach ($clauses->p as $i => $clause) {
                                    $clauses->p[$i] = match ($clause) {
                                        '%CURRENT_EMPL_ID%' => $salary->empl_id,
                                        '%START_AT%' => $salary->start_at->format('Y-m-d'),
                                        '%END_AT%' => $salary->end_at->format('Y-m-d'),
                                        '%COMPONENT_ID%' => $component->id,
                                        default => $clause
                                    };
                                }
                                $query = $query->{$clauses->f}(...$clauses->p);
                            }
                            // dd($query->toSql(), $query->getBindings(), $query->first(), $config->action, $config->action_column);
                            $amount += $query->{$config->action}($config->action_column);
                        }
                        break;

                    default:
                        $multiplier = $component->meta?->as_multiplier ?? 1;
                        $amount = $component->amount ?? 0;
                        break;
                }
            } else {
                $multiplier = $items->firstWhere('component_id', $component->id)['n'] ?? 0;
                $amount = $items->firstWhere('component_id', $component->id)['amount'] ?? 0;
            }

            $component->multiplier = $multiplier;
            $component->default_amount = $amount;

            return $component;
        });

        return view('finance::payroll.pph.create', [
            'salary' => $salary,
            'components' => $components,
            'start_at' => $salary->start_at,
            'end_at' => $salary->end_at,
            'selectedTemplate' => $selectedTemplate,
            'employee' => $salary->employee->load('position')
        ]);
    }

    /**
     * Update validations.
     */
    public function update(EmployeeSalary $salary, UpdateRequest $request)
    {
        $salary->fill($request->transformed()->toArray());
        if ($salary->save()) {
            return redirect()->next()->with('success', 'Validasi untuk gaji <strong>' . $salary->name . '</strong> untuk <strong>' . $salary->employee->name . '</strong> berhasil diperbarui!');
        };
        return redirect()->fail();
    }
}
