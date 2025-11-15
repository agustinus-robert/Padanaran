<?php

namespace Modules\Finance\Http\Controllers\Summary;

use Illuminate\Support\Arr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Enums\ReimbursementMethodEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\Core\Models\CompanyReimbursementCategory;
use Modules\HRMS\Enums\DataRecapitulationTypeEnum;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeDataRecapitulation;
use Modules\Finance\Http\Requests\Summary\Reimbursement\StoreRequest;
use Modules\HRMS\Models\EmployeeReimbursement;

class ReimbursementController extends Controller
{
    /**
     * Show the index page.
     */
    public function index(Request $request)
    {
        EmployeeReimbursement::with('approvables')->whereNull('paidable_at')->get()->each(
            fn($r) => $r->update([
                'paidable_at' => $r->approvables->count() ? (($a = $r->approvables->sortByDesc('level')->first())->result == ApprovableResultEnum::APPROVE ? $a->updated_at : null) : now()
            ])
        );

        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        return view('finance::summary.reimbursements.index', [
            'start_at'    => $start_at,
            'end_at'      => $end_at,
            'departments' => CompanyDepartment::visible()->with('positions')->get(),
            'employees'   => Employee::with(['user', 'contract.position.position', 'dataRecapitulations' => fn($recap) => $recap->whereType(DataRecapitulationTypeEnum::REIMBURSEMENT)->whereStrictPeriodIn($start_at, $end_at)])
                ->whereHas('contract')
                ->whereHas('reimbursements', fn($reimbursement) => $reimbursement->whereBetween('paidable_at', [$start_at, $end_at]))
                ->withCount(['reimbursements' => fn($reimbursement) => $reimbursement->whereBetween('paidable_at', [$start_at, $end_at])])
                ->whenPositionOfDepartment($request->get('department'), $request->get('position'))
                ->search($request->get('search'))
                ->paginate($request->get('limit', 10))
        ]);
    }

    /**
     * Create resource    
     */
    public function create(Request $request)
    {
        return view('finance::summary.reimbursements.create', [
            'employee'   => ($employee = Employee::findOrFail($request->get('employee'))),
            'start_at'   => ($start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00')),
            'end_at'     => ($end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59')),
            'categories' => CompanyReimbursementCategory::get(),
            'schedules'  => ($schedules = $employee->schedules()->wherePeriodIn($start_at, $end_at)->pluck('dates')->flatMap(fn($v) => $v)),
            'reimbursements' => $employee->reimbursements()->with('category')->where('method', ReimbursementMethodEnum::PAYROLL)->whereBetween('paidable_at', [$start_at, $end_at])->get()
        ]);
    }

    public function store(StoreRequest $request)
    {
        $employee = Employee::find($request->input('employee'));
        $recap = $employee->dataRecapitulations()->create([
            'type' => DataRecapitulationTypeEnum::REIMBURSEMENT,
            ...$request->transformed()->toArray()
        ]);

        foreach ($request->transformed()->input('reimbursements') as $reimbursement) {
            $employee->reimbursements()->find($reimbursement['id'])->update([
                'paid_off_at' => $reimbursement['paid_off_at'],
                'paid_amount' => $reimbursement['total']
            ]);
        }
        $request->user()->log('membuat rekap ' . count($request->input('reimbursements', [])) . ' reimbursement karyawan atas nama ' . $employee->user->name, EmployeeDataRecapitulation::class, $recap->id);
        return redirect()->next()->with('success', 'Rekap total <strong>' . count($request->input('reimbursements', [])) . '</strong> reimbursement karyawan atas nama <strong>' . $employee->user->name . '</strong> berhasi dibuat!');
    }

    public function destroy(Employee $employee, Request $request)
    {
        $reimbursements = explode(',', $request->get('reimbursements'));
        $employee->dataRecapitulations()->whereType(DataRecapitulationTypeEnum::REIMBURSEMENT)->whereIn('id', $reimbursements)->get()->each(
            fn($recap) => EmployeeReimbursement::whereIn('id', array_column($recap->result->reimbursements, 'id'))->update([
                'paid_off_at' => null,
                'paid_amount' => null
            ])
        );
        $employee->dataRecapitulations()->whereType(DataRecapitulationTypeEnum::REIMBURSEMENT)->whereIn('id', $reimbursements)->delete();
        $request->user()->log('menghapus rekap ' . count($reimbursements) . ' reimbursement karyawan atas nama ' . $employee->user->name, Employee::class, $employee->id);
        return redirect()->next()->with('success', 'Rekap total <strong>' . count($reimbursements) . '</strong> reimbursement karyawan atas nama <strong>' . $employee->user->name . '</strong> berhasi dihapus!');
    }
}
