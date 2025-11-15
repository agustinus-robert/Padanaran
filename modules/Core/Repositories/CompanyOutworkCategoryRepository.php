<?php

namespace Modules\Core\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Models\CompanyOutworkCategory;

trait CompanyOutworkCategoryRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = ['name', 'description', 'price', 'meta', 'grade_id'];

    /**
     * Store newly created resource.
     */
    public function storeCompanyOutworkCategory(array $data)
    {
        $category = new CompanyOutworkCategory(array_merge(
           Arr::only($data, $this->keys),
            ['grade_id' => userGrades()]
        ));

        if ($category->save()) {
            Auth::user()->log('membuat kategori kegiatan lainnya dengan nama ' . $category->name . ' <strong>[ID: ' . $category->id . ']</strong>', CompanyOutworkCategory::class, $category->id);
            return $category;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanyOutworkCategory(CompanyOutworkCategory $category, array $data)
    {
        $category = $category->fill(array_merge(
           Arr::only($data, $this->keys),
            ['grade_id' => userGrades()]
        ));
        
        if ($category->save()) {
            Auth::user()->log('memperbarui kategori kegiatan lainnya ' . $category->name . ' <strong>[ID: ' . $category->id . ']</strong>', CompanyOutworkCategory::class, $category->id);
            return $category;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanyOutworkCategory(CompanyOutworkCategory $category)
    {
        if (!$category->trashed() && $category->delete()) {
            Auth::user()->log('menghapus kategori kegiatan lainnya ' . $category->name . ' <strong>[ID: ' . $category->id . ']</strong>', CompanyOutworkCategory::class, $category->id);
            return $category;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanyOutworkCategory(CompanyOutworkCategory $category)
    {
        if ($category->trashed() && $category->restore()) {
            Auth::user()->log('memulihkan kategori kegiatan lainnya ' . $category->name . ' <strong>[ID: ' . $category->id . ']</strong>', CompanyOutworkCategory::class, $category->id);
            return $category;
        }
        return false;
    }
}
