<?php

namespace Modules\Core\Http\Controllers\Company\Service;

use Illuminate\Http\Request;
use Modules\Core\Models\CompanyOutworkCategory;
use Modules\Core\Http\Requests\Company\Service\OutworkCategory\StoreRequest;
use Modules\Core\Http\Requests\Company\Service\OutworkCategory\UpdateRequest;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Repositories\CompanyOutworkCategoryRepository;

class OutworkCategoryController extends Controller
{
    use CompanyOutworkCategoryRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', CompanyOutworkCategory::class);

        $categories = CompanyOutworkCategory::search($request->get('search'))
            ->where('grade_id', userGrades())
            ->whenTrashed($request->get('trash'))
            ->paginate($request->get('limit', 10));

        $categories_count = CompanyOutworkCategory::where('grade_id', userGrades())->count();

        return view('core::company.services.outwork-categories.index', compact('categories', 'categories_count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('store', CompanyOutworkCategory::class);

        return view('core::company.services.outwork-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($category = $this->storeCompanyOutworkCategory($request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Kategori izin dengan nama <strong>' . $category->name . '</strong> telah berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyOutworkCategory $category)
    {
        $this->authorize('update', $category);

        $categories = CompanyOutworkCategory::where('grade_id', userGrades())->get();

        return view('core::company.services.outwork-categories.show', compact('categories', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyOutworkCategory $category, UpdateRequest $request)
    {
        if ($category = $this->updateCompanyOutworkCategory($category, $request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Kategori izin dengan nama <strong>' . $category->name . '</strong> telah berhasil diperbarui.');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyOutworkCategory $category)
    {
        $this->authorize('destroy', $category);

        if ($category = $this->destroyCompanyOutworkCategory($category)) {
            return redirect()->next()->with('success', 'Kategori izin dengan nama <strong>' . $category->name . '</strong> telah berhasil dihapus.');
        }
        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(CompanyOutworkCategory $category)
    {
        $this->authorize('restore', $category);

        if ($category = $this->restoreCompanyOutworkCategory($category)) {
            return redirect()->next()->with('success', 'Kategori izin dengan nama <strong>' . $category->name . '</strong> telah berhasil dipulihkan.');
        }
        return redirect()->fail();
    }
}
