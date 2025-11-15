<?php

namespace Modules\Core\Http\Controllers\Company\Service;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyStudentLeaveCategory;
use Modules\Core\Http\Requests\Company\Service\LeaveCategory\StoreRequest;
use Modules\Core\Http\Requests\Company\Service\LeaveCategory\UpdateRequest;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Repositories\CompanyStudentLeaveCategoryRepository;

class LeaveStudentCategoryController extends Controller
{
    use CompanyStudentLeaveCategoryRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', CompanyStudentLeaveCategory::class);

        $categories = CompanyStudentLeaveCategory::with('parent')
            ->where('grade_id', userGrades())
            ->whenTrashed($request->get('trash'))
            ->search($request->get('search'))
            ->paginate($request->get('limit', 10));

        $categories_count = CompanyStudentLeaveCategory::count();

        return view('core::company.services.leave-student-categories.index', compact('categories', 'categories_count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('store', CompanyStudentLeaveCategory::class);

        $categories = CompanyStudentLeaveCategory::where('grade_id', userGrades())->whereNull('parent_id')->get();

        return view('core::company.services.leave-student-categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($category = $this->storeCompanyLeaveCategory($request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Kategori izin dengan nama <strong>' . $category->name . '</strong> telah berhasil dibuat.');
        }

        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyStudentLeaveCategory $category)
    {
        $this->authorize('update', $category);

        $categories = CompanyStudentLeaveCategory::where('grade_id', userGrades())->whereNull('parent_id')->get();

        return view('core::company.services.leave-student-categories.show', compact('categories', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyStudentLeaveCategory $category, UpdateRequest $request)
    {
        if ($category = $this->updateCompanyLeaveCategory($category, $request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Kategori izin dengan nama <strong>' . $category->name . '</strong> telah berhasil diperbarui.');
        }

        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyStudentLeaveCategory $category)
    {
        $this->authorize('destroy', $category);

        if ($category = $this->destroyCompanyLeaveCategory($category)) {

            return redirect()->next()->with('success', 'Kategori izin dengan nama <strong>' . $category->name . '</strong> telah berhasil dihapus.');
        }

        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(CompanyStudentLeaveCategory $category)
    {
        $this->authorize('restore', $category);

        if ($category = $this->restoreCompanyLeaveCategory($category)) {

            return redirect()->next()->with('success', 'Kategori izin dengan nama <strong>' . $category->name . '</strong> telah berhasil dipulihkan.');
        }

        return redirect()->fail();
    }
}
