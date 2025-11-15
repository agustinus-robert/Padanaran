<?php

namespace Modules\Core\Repositories;

use Arr;
use Auth;
use Modules\Core\Models\CompanyReimbursementCategory;

trait CompanyReimbursementCategoryRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'name', 'limit', 'meta'
    ];

    /**
     * Store newly created resource.
     */
    public function storeCompanyReimbursementCategory(array $data)
    {
        $category = new CompanyReimbursementCategory(Arr::only($data, $this->keys));
        if($category->save()) {
            Auth::user()->log('membuat kategori reimbursement baru dengan nama '.$category->name.' <strong>[ID: '.$category->id.']</strong>', CompanyReimbursementCategory::class, $category->id);
            return $category;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanyReimbursementCategory(CompanyReimbursementCategory $category, array $data)
    {        
        $category = $category->fill(Arr::only($data, $this->keys));
        if($category->save()) {
            Auth::user()->log('memperbarui kategori reimbursement '.$category->name.' <strong>[ID: '.$category->id.']</strong>', CompanyReimbursementCategory::class, $category->id);
            return $category;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanyReimbursementCategory(CompanyReimbursementCategory $category)
    {
        if(!$category->trashed() && $category->delete()) {
            Auth::user()->log('menghapus kategori reimbursement '.$category->name.' <strong>[ID: '.$category->id.']</strong>', CompanyReimbursementCategory::class, $category->id);
            return $category;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanyReimbursementCategory(CompanyReimbursementCategory $category)
    {
        if($category->trashed() && $category->restore()) {
            Auth::user()->log('memulihkan kategori reimbursement '.$category->name.' <strong>[ID: '.$category->id.']</strong>', CompanyReimbursementCategory::class, $category->id);
            return $category;
        }
        return false;
    }
}