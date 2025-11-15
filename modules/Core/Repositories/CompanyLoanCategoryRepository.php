<?php

namespace Modules\Core\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Models\CompanyLoanCategory;

trait CompanyLoanCategoryRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = ['type', 'name', 'description', 'interest_id', 'meta'];

    /**
     * Store newly created resource.
     */
    public function storeCompanyLoanCategory(array $data)
    {
        $category = new CompanyLoanCategory(Arr::only($data, $this->keys));
        if ($category->save()) {
            Auth::user()->log('membuat kategori kegiatan lainnya dengan nama ' . $category->name . ' <strong>[ID: ' . $category->id . ']</strong>', CompanyLoanCategory::class, $category->id);
            return $category;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanyLoanCategory(CompanyLoanCategory $category, array $data)
    {
        $category = $category->fill(Arr::only($data, $this->keys));
        if ($category->save()) {
            Auth::user()->log('memperbarui kategori kegiatan lainnya ' . $category->name . ' <strong>[ID: ' . $category->id . ']</strong>', CompanyLoanCategory::class, $category->id);
            return $category;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanyLoanCategory(CompanyLoanCategory $category)
    {
        if (!$category->trashed() && $category->delete()) {
            Auth::user()->log('menghapus kategori kegiatan lainnya ' . $category->name . ' <strong>[ID: ' . $category->id . ']</strong>', CompanyLoanCategory::class, $category->id);
            return $category;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanyLoanCategory(CompanyLoanCategory $category)
    {
        if ($category->trashed() && $category->restore()) {
            Auth::user()->log('memulihkan kategori kegiatan lainnya ' . $category->name . ' <strong>[ID: ' . $category->id . ']</strong>', CompanyLoanCategory::class, $category->id);
            return $category;
        }
        return false;
    }
}
