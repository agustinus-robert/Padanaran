<?php

namespace Modules\Finance\Http\Controllers\Payroll;

use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Models\CompanySalarySlipComponent;
use Modules\Core\Models\CompanySalaryTemplate;
use Modules\HRMS\Models\Employee;

class ExportTemplateController extends TemplateController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start_at = $request->get('start_at') ? Carbon::parse($request->get('start_at')) : Carbon::parse(date('Y-01-01 H:i:s'));
        $end_at = $request->get('end_at') ? Carbon::parse($request->get('end_at')) : Carbon::parse(date('Y-12-31 H:i:s'));
        $period = $start_at->isSameDay($end_at) ? $end_at->isoFormat('DD MMM YYYY') : $start_at->isoFormat('DD MMM') . ' s.d. ' . $end_at->isoFormat('DD MMM YYYY');

        $thr_templates = CompanySalarySlipComponent::whereIn('id', [1, 2, 3, 4])->get()->mapWithKeys(function ($item, $key) {
            return ['thr_' . $item['id'] => $item['name']];
        })->toArray();

        $g13_templates = CompanySalarySlipComponent::whereIn('id', [1, 2, 3, 4, 5])->get()->mapWithKeys(function ($item, $key) {
            return ['g13_' . $item['id'] => $item['name']];
        })->toArray();

        $employees = Employee::with([
            'user',
            'position.position.department',
            'salaryTemplates' => fn($template) => $template->with(['components' => fn($x) => $x->where('ctg_id', 1)])->where('cmp_template_id', CompanySalaryTemplate::whereName('Gaji Bulanan')->first()->id)->whereDate('start_at', '>=', $start_at->format('Y-m-d'))->whereDate('end_at', '<=', $end_at->format('Y-m-d'))
        ])->has('contract')->get();

        return response()->json([
            'title' => ($title = 'Rekap template gaji karyawan periode ' . $period),
            'subtitle' => 'Diunduh pada ' . now()->isoFormat('LLLL'),
            'file' => Str::slug($title . '-' . time()),
            'thr' => $thr_templates,
            'g13' => $g13_templates,
            'columns' => [
                'no' => 'No',
                'name' => 'Nama',
                'npwp' => 'NPWP',
                'department' => 'Departemen',
                'position' => 'Jabatan',
                ...($columns = [
                    ...$thr_templates,
                    ...$g13_templates
                ])
            ],
            'rows' => $employees->map(function ($employee, $i) use ($columns) {
                $template = $employee->salaryTemplates->first();
                $references = !isset($template->components) ? [] : $template->components->whereIn('id', [1, 2, 3, 4, 5])->mapWithKeys(function ($item) {
                    return [
                        'thr_' . $item->id => $item->pivot->amount,
                        'g13_' . $item->id => $item->pivot->amount,
                    ];
                });
                foreach ($columns as $key => $amount) {
                    $salaries[$key] = $references[$key] ?? '';
                }
                return [
                    'no' => $i + 1,
                    'name' => $employee->user->name,
                    'npwp' => $employee->user->getMeta('tax_number'),
                    'department' => $employee->position->position->department->name,
                    'position' => $employee->position->position->name,
                    ...$salaries
                ];
            })
        ]);
    }
}
