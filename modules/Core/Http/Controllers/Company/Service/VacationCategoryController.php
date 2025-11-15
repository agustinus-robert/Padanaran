<?php

namespace Modules\Core\Http\Controllers\Company\Service;

use Illuminate\Http\Request;
use Modules\Core\Enums\VacationTypeEnum;
use Modules\Core\Models\CompanyVacationCategory;
use Modules\Core\Http\Requests\Company\Service\VacationCategory\StoreRequest;
use Modules\Core\Http\Requests\Company\Service\VacationCategory\UpdateRequest;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Repositories\CompanyVacationCategoryRepository;

class VacationCategoryController extends Controller
{
    use CompanyVacationCategoryRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', CompanyVacationCategory::class);

        $categories = CompanyVacationCategory::whenTrashed($request->get('trash'))
                                        ->where('grade_id', userGrades())
                                        ->search($request->get('search'))
                                        ->paginate($request->get('limit', 10));

        $categories_count = CompanyVacationCategory::where('grade_id', userGrades())->count();

        return view('core::company.services.vacation-categories.index', compact('categories', 'categories_count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('store', CompanyVacationCategory::class);

        $types = VacationTypeEnum::cases();

        return view('core::company.services.vacation-categories.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if($category = $this->storeCompanyVacationCategory($request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Kategori cuti dengan nama <strong>'.$category->name.'</strong> telah berhasil dibuat.');

        }

        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyVacationCategory $category)
    {
        $this->authorize('update', $category);

        $types = VacationTypeEnum::cases();

        return view('core::company.services.vacation-categories.show', compact('category', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyVacationCategory $category, UpdateRequest $request)
    {
        if($category = $this->updateCompanyVacationCategory($category, $request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Kategori cuti dengan nama <strong>'.$category->name.'</strong> telah berhasil diperbarui.');

        }

        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyVacationCategory $category)
    {
        $this->authorize('destroy', $category);

        if($category = $this->destroyCompanyVacationCategory($category)) {

            return redirect()->next()->with('success', 'Kategori cuti dengan nama <strong>'.$category->name.'</strong> telah berhasil dihapus.');

        }

        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(CompanyVacationCategory $category)
    {
        $this->authorize('restore', $category);

        if($category = $this->restoreCompanyVacationCategory($category)) {

            return redirect()->next()->with('success', 'Kategori cuti dengan nama <strong>'.$category->name.'</strong> telah berhasil dipulihkan.');

        }

        return redirect()->fail();

    }
}
