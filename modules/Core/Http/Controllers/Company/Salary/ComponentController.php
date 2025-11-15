<?php

namespace Modules\Core\Http\Controllers\Company\Salary;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanySalarySlip;
use Modules\Core\Models\CompanySalarySlipCategory;
use Modules\Core\Models\CompanySalarySlipComponent;
use Modules\Core\Enums\SalaryUnitEnum;
use Modules\Core\Enums\SalaryOperateEnum;
use Modules\Core\Http\Requests\Company\Salary\Component\StoreRequest;
use Modules\Core\Http\Requests\Company\Salary\Component\UpdateRequest;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Repositories\CompanySalarySlipComponentRepository;

class ComponentController extends Controller
{
    use CompanySalarySlipComponentRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', CompanySalarySlipComponent::class);

        return view('core::company.salaries.components.index', [
            'components'   => $this->getCompanySalarySlipComponent($request),
            'components_count'   => CompanySalarySlipComponent::where('grade_id', userGrades())->count(),
            'slips'      => CompanySalarySlip::where('grade_id', userGrades())->with('categories')->get(),
            'units'      => SalaryUnitEnum::cases(),
            'operates'   => SalaryOperateEnum::cases(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($salary = $this->storeCompanySalarySlipComponent($request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Kategori <strong>' . $salary->name . '</strong> telah berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanySalarySlipComponent $component)
    {
        $this->authorize('update', $component);
        return view('core::company.salaries.components.show', [
            'salary'     => $component,
            'categories' => CompanySalarySlipCategory::where('grade_id', userGrades())->get(),
            'slips'      => CompanySalarySlip::where('grade_id', userGrades())->get(),
            'units'      => SalaryUnitEnum::cases(),
            'operates'   => SalaryOperateEnum::cases(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanySalarySlipComponent $component, UpdateRequest $request)
    {
        if ($salary = $this->updateCompanySalarySlipComponent($component, $request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Kategori gaji dengan nama <strong>' . $salary->name . '</strong> telah berhasil diperbarui.');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanySalarySlipComponent $component)
    {
        $this->authorize('destroy', $component);

        if ($salary = $this->destroyCompanySalarySlipComponent($component)) {

            return redirect()->next()->with('success', 'Kategori gaji dengan nama <strong>' . $salary->name . '</strong> telah berhasil dihapus.');
        }
        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(CompanySalarySlipComponent $component)
    {
        $this->authorize('restore', $component);

        if ($salary = $this->restoreCompanySalarySlipComponent($component)) {

            return redirect()->next()->with('success', 'Kategori gaji dengan nama <strong>' . $salary->name . '</strong> telah berhasil dipulihkan.');
        }
        return redirect()->fail();
    }
}
