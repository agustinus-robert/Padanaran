<?php

namespace Modules\Core\Http\Controllers\Company\Salary;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanySalarySlip;
use Modules\Core\Models\CompanySalarySlipCategory;
use Modules\Core\Http\Requests\Company\Salary\Category\StoreRequest;
use Modules\Core\Http\Requests\Company\Salary\Category\UpdateRequest;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Repositories\CompanySalarySlipCategoryRepository;

class CategoryController extends Controller
{
    use CompanySalarySlipCategoryRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', CompanySalarySlipCategory::class);
        return view('core::company.salaries.categories.index', [
            'categories' => $this->getCompanySalaryCategories($request),
            'slips'      => CompanySalarySlip::where('grade_id', userGrades())->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($category = $this->storeCompanySalarySlipCategory($request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Kategori <strong>' . $category->name . '</strong> telah berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanySalarySlipCategory $category)
    {
        $this->authorize('update', $category);
        return view('core::company.salaries.categories.show', [
            'category'  => $category,
            'slips'     => CompanySalarySlip::where('grade_id', userGrades())->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanySalarySlipCategory $category, UpdateRequest $request)
    {
        if ($category = $this->updateCompanySalarySlipCategory($category, $request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Kategori gaji dengan nama <strong>' . $category->name . '</strong> telah berhasil diperbarui.');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanySalarySlipCategory $category)
    {
        $this->authorize('destroy', $category);

        if ($category = $this->destroyCompanySalarySlipCategory($category)) {

            return redirect()->next()->with('success', 'Kategori gaji dengan nama <strong>' . $category->name . '</strong> telah berhasil dihapus.');
        }
        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(CompanySalarySlipCategory $category)
    {
        $this->authorize('restore', $category);

        if ($category = $this->restoreCompanySalarySlipCategory($category)) {

            return redirect()->next()->with('success', 'Kategori gaji dengan nama <strong>' . $category->name . '</strong> telah berhasil dipulihkan.');
        }
        return redirect()->fail();
    }
}
