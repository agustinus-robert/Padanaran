<?php

namespace Modules\Core\Http\Controllers\Company\Insurance;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyInsurance;
use Modules\Core\Models\CompanyInsurancePrice;
use Modules\Core\Http\Requests\Company\Insurance\StoreRequest;
use Modules\Core\Http\Requests\Company\Insurance\UpdateRequest;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Repositories\CompanyInsuranceRepository;

class ManageController extends Controller
{
    use CompanyInsuranceRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', CompanyInsurance::class);

        return view('core::company.insurances.manages.index', [
            'insurances' => CompanyInsurance::with('prices')->where('grade_id', userGrades())->paginate($request->get('limit', 10)),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($category = $this->storeCompanySalaryCategory($request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Kategori <strong>' . $category->name . '</strong> telah berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanySalaryCategory $category)
    {
        $this->authorize('update', $category);
        return view('core::company.salaries.categories.show', [
            'category'  => $category,
            'slips'     => CompanySalarySlip::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanySalaryCategory $category, UpdateRequest $request)
    {
        if ($category = $this->updateCompanySalaryCategory($category, $request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Kategori gaji dengan nama <strong>' . $category->name . '</strong> telah berhasil diperbarui.');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanySalaryCategory $category)
    {
        $this->authorize('destroy', $category);

        if ($category = $this->destroyCompanySalaryCategory($category)) {

            return redirect()->next()->with('success', 'Kategori gaji dengan nama <strong>' . $category->name . '</strong> telah berhasil dihapus.');
        }
        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(CompanySalaryCategory $category)
    {
        $this->authorize('restore', $category);

        if ($category = $this->restoreCompanySalaryCategory($category)) {

            return redirect()->next()->with('success', 'Kategori gaji dengan nama <strong>' . $category->name . '</strong> telah berhasil dipulihkan.');
        }
        return redirect()->fail();
    }
}
