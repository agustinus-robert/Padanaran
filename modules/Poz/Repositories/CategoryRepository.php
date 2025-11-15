<?php

namespace Modules\Poz\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\Category;
use Illuminate\Support\Str;


trait CategoryRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'code',
        'name',
        'slug',
        'description',
        'parent_id',
        'location',
        'image_name'
    ];

    /**
     * Store newly created resource.
     */
    public function storeCategory(array $data)
    {
        $data['slug'] = str_replace(' ', '-', strtolower($data['name']));
        $data['parent_id'] = (empty($data['parent_id']) ? null : $data['parent_id']);

        $location = 'file_category/' . uniqid();

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image']->storeAs($location, $data['image']->getClientOriginalName(), 'public');
            $data['location'] = $location;
            $data['image_name'] = $data['image']->getClientOriginalName();
        } else {
            $data['location'] = 'dummy/';
            $data['image_name'] = 'no-pictures.png';
        }
        $data['slug'] = Str::slug($data['name']);

        $category = new Category(Arr::only($data, $this->keys));
        if ($category->save()) {
            $outletId = $data['outlet'];

            if ($outletId) {
                $category->outlets()->attach($outletId);
            }

            return true;
        }

        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCategory(array $data, $id)
    {
        $category = Category::find($id);
        $data['slug'] = str_replace(' ', '-', strtolower($data['name']));
        $data['parent_id'] = (empty($data['parent_id']) ? null : $data['parent_id']);

        $location = 'file_category/' . uniqid();

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['location'] = $location;
            $data['image']->storeAs($location, $data['image']->getClientOriginalName(), 'public');
            $data['image_name'] = $data['image']->getClientOriginalName();
        }
        $data['slug'] = Str::slug($data['name']);

        if ($category->update(Arr::only($data, $this->keys))) {
            $outletId = $data['outlet'];

            if ($outletId) {
                $category->outlets()->syncWithoutDetaching($outletId);
            }

            return true;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCategory($id)
    {
        if (Category::where('id', $id)->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCategory($id)
    {
        if (Category::onlyTrashed()->find($id)->restore()) {
            return true;
        }
        return false;
    }
}
