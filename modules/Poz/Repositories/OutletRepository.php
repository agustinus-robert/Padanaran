<?php

namespace Modules\Poz\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\Outlet;

trait OutletRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'code',
        'admin_id',
        'name',
        'description',
        'location',
        'image_name'
    ];

    /**
     * Store newly created resource.
     */
    public function storeOutlet(array $data)
    {
        $location = 'file_outlet/' . uniqid();

        if (isset($data['document']) && $data['document'] instanceof \Illuminate\Http\UploadedFile) {
            $data['document']->storeAs($location, $data['document']->getFilename(), 'public');
            $data['location'] = $location;
            $data['image_name'] = $data['document']->getFilename();
        }

        $outlet = new Outlet(Arr::only($data, $this->keys));
        if ($outlet->save()) {
            return true;
        }

        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateOutlet(array $data, $id)
    {
        $outlet = Outlet::find($id);
        $location = 'file_outlet/' . uniqid();

        if (isset($data['document']) && $data['document'] instanceof \Illuminate\Http\UploadedFile) {
            $data['document']->storeAs($location, $data['document']->getFilename(), 'public');
            $data['location'] = $location;
            $data['image_name'] = $data['document']->getFilename();
        }


        if ($outlet->update(Arr::only($data, $this->keys))) {
            return true;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyOutlet($id)
    {
        if (Outlet::where('id', $id)->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreOutlet($id)
    {
        if (Outlet::onlyTrashed()->find($id)->restore()) {
            return true;
        }
        return false;
    }
}
