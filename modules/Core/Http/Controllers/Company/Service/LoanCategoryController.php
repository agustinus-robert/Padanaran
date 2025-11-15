<?php

namespace Modules\Core\Http\Controllers\Company\Service;

use Illuminate\Http\Request;
use Modules\Core\Enums\LoanTypeEnum;
use Modules\Core\Models\CompanyLoanCategory;
use Modules\Core\Http\Requests\Company\Service\LoanCategory\StoreRequest;
use Modules\Core\Http\Requests\Company\Service\LoanCategory\UpdateRequest;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Repositories\CompanyLoanCategoryRepository;

class LoanCategoryController extends Controller
{
    use CompanyLoanCategoryRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', CompanyLoanCategory::class);

        $categories = CompanyLoanCategory::search($request->get('search'))
            ->whenTrashed($request->get('trash'))
            ->paginate($request->get('limit', 10));

        $categories_count = CompanyLoanCategory::count();

        return view('core::company.services.loan-categories.index', compact('categories', 'categories_count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('store', CompanyLoanCategory::class);
        $categories = CompanyLoanCategory::all();
        $types = LoanTypeEnum::cases();

        return view('core::company.services.loan-categories.create', compact('types', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($category = $this->storeCompanyLoanCategory($request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Kategori izin dengan nama <strong>' . $category->name . '</strong> telah berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyLoanCategory $category)
    {
        $this->authorize('update', $category);

        $categories = CompanyLoanCategory::get();
        $types = LoanTypeEnum::cases();

        return view('core::company.services.loan-categories.show', compact('categories', 'category', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyLoanCategory $category, UpdateRequest $request)
    {
        if ($category = $this->updateCompanyLoanCategory($category, $request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Kategori izin dengan nama <strong>' . $category->name . '</strong> telah berhasil diperbarui.');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyLoanCategory $category)
    {
        $this->authorize('destroy', $category);

        if ($category = $this->destroyCompanyLoanCategory($category)) {
            return redirect()->next()->with('success', 'Kategori izin dengan nama <strong>' . $category->name . '</strong> telah berhasil dihapus.');
        }
        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(CompanyLoanCategory $category)
    {
        $this->authorize('restore', $category);

        if ($category = $this->restoreCompanyLoanCategory($category)) {
            return redirect()->next()->with('success', 'Kategori izin dengan nama <strong>' . $category->name . '</strong> telah berhasil dipulihkan.');
        }
        return redirect()->fail();
    }
}
