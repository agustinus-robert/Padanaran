<?php

namespace Modules\Core\Http\Controllers\Company\Salary;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanySalarySlip;
use Modules\Core\Http\Requests\Company\Salary\Slip\StoreRequest;
use Modules\Core\Http\Requests\Company\Salary\Slip\UpdateRequest;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Repositories\CompanySalarySlipRepository;

class SlipController extends Controller
{
    use CompanySalarySlipRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', CompanySalarySlip::class);

        return view('core::company.salaries.slips.index', [
            'slips' => $this->getCompanySalarySlip($request),
            'slips_count' => CompanySalarySlip::where('grade_id', userGrades())->count()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($slip = $this->storeCompanySalarySlip($request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Departemen dengan nama <strong>' . $slip->name . ' (' . $slip->kd . ')</strong> telah berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanySalarySlip $slip)
    {
        $this->authorize('update', $slip);

        return view('core::company.salaries.slips.show', [
            'slip'  => $slip
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanySalarySlip $slip, UpdateRequest $request)
    {
        if ($slip = $this->updateCompanySalarySlip($slip, $request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Departemen dengan nama <strong>' . $slip->name . '</strong> telah berhasil diperbarui.');
        }

        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanySalarySlip $slip)
    {
        $this->authorize('destroy', $slip);

        if ($slip = $this->destroyCompanySalarySlip($slip)) {

            return redirect()->next()->with('success', 'Departemen dengan nama <strong>' . $slip->name . '</strong> telah berhasil dihapus.');
        }

        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(CompanySalarySlip $slip)
    {
        $this->authorize('restore', $slip);

        if ($slip = $this->restoreCompanySalarySlip($slip)) {

            return redirect()->next()->with('success', 'Departemen dengan nama <strong>' . $slip->name . '</strong> telah berhasil dipulihkan.');
        }

        return redirect()->fail();
    }
}
