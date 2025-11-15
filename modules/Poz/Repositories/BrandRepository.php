<?php

namespace Modules\Poz\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\Brand;
use Illuminate\Support\Str;

trait BrandRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'code',
        'name',
        'slug',
        'description',
        'location',
        'image_name'
    ];

    /**
     * Store newly created resource.
     */
    public function storeBrand(array $data)
    {
        $location = 'file_brand/' . uniqid();

        if (isset($data['document']) && $data['document'] instanceof \Illuminate\Http\UploadedFile) {
            $data['document']->storeAs($location, $data['document']->getFilename(), 'public');
            $data['location'] = $location;
            $data['image_name'] = $data['document']->getFilename();
        }

        $data['slug'] = Str::slug($data['name']);
        $brand = new Brand(Arr::only($data, $this->keys));
        if ($brand->save()) {
            $outletId = $data['outlet'];

            if ($outletId) {
                $brand->outlets()->attach($outletId);
            }

            return true;
        }

        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateBrand(array $data, $id)
    {
        $brand = Brand::find($id);
        $location = 'file_brand/' . uniqid();

        if (isset($data['document']) && $data['document'] instanceof \Illuminate\Http\UploadedFile) {
            $data['document']->storeAs($location, $data['document']->getFilename(), 'public');
            $data['location'] = $location;
            $data['image_name'] = $data['document']->getFilename();
        }
        $data['slug'] = Str::slug($data['name']);

        if ($brand->update(Arr::only($data, $this->keys))) {
            $outletId = $data['outlet'];

            if ($outletId) {
                $brand->outlets()->syncWithoutDetaching($outletId);
            }

            return true;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyInquiry($id)
    {
        if (Brand::where('id', $id)->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreInquiry($id)
    {
        if (Brand::onlyTrashed()->find($id)->restore()) {
            return true;
        }
        return false;
    }
}
