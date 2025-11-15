<?php

namespace Modules\Finance\Http\Controllers\Tax;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\EmployeeSalary;
use Modules\HRMS\Models\EmployeeTax;
use Modules\HRMS\Enums\TaxTypeEnum;
use Modules\Finance\Http\Requests\Report\Tax\StoreRequest;

class IncometaxController extends Controller
{
    /**
     * Show the index page.
     */
    public function index(Request $request)
    {
        $this->authorize('access', EmployeeSalary::class);

        return view('finance::tax.incomes.index', [
            'start_at'    => $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00'),
            'end_at'      => $end_at = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59'),
            'departments' => CompanyDepartment::visible()->where('grade_id', userGrades())->with('positions')->get(),
            'types'       => collect(TaxTypeEnum::cases()),
            'taxs'        => EmployeeTax::where('type', TaxTypeEnum::YEARLY)->whereHas('employee', function($query){
                $query->where('grade_id', userGrades());
            })->whenPeriod($start_at, $end_at)->whenTrashed($request->get('trashed'))->paginate($request->get('limit', 10)),
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('access', EmployeeSalary::class);

        return view('finance::tax.incomes.create', [
            'start_at'    => $start_at = Carbon::parse($request->get('start_at', date('Y-01-01')) . ' 00:00:00'),
            'end_at'      => $end_at = Carbon::parse($request->get('end_at', date('Y-12-31')) . ' 23:59:59'),
            'types'       => collect(TaxTypeEnum::cases()),
        ]);
    }

    public function store(StoreRequest $request)
    {
        $tax = new EmployeeTax($request->transformed()->toArray());

        if ($tax->save()) {
            return redirect()->next()->with('success', 'Bukti potong PPh 21 <strong>' . $tax->employee->user->name . '</strong> berhasil dibuat.');
        }
        return redirect()->fail();
    }

    public function show()
    {
        // 
    }

    public function release(Request $request)
    {
        $start_at = Carbon::parse($request->get('start_at', cmp_cutoff(0)->format('Y-m-d')) . ' 00:00:00');
        $end_at   = Carbon::parse($request->get('end_at', cmp_cutoff(1)->format('Y-m-d')) . ' 23:59:59');

        if (EmployeeTax::where('type', TaxTypeEnum::YEARLY)->whenPeriod($start_at, $end_at)->update(['released_at' => now()])) {
            return redirect()->next()->with('success', 'Bukti potong PPh 21 berhasil diterbitkan.');
        }
        return redirect()->fail();
    }

    public function destroy(EmployeeTax $income)
    {
        if ($income->delete()) {
            return redirect()->next()->with('success', 'Bukti potong PPh 21 berhasil dihapus.');
        }
        return redirect()->fail();
    }
}
