<?php

namespace Modules\Poz\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\Tax;

trait TaxDirectRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'actived_on'
    ];

    /**
     * Update the current resource.
     */
    public function updateTax(array $data, $id)
    {
        $tax = Tax::find($id);
        $data['actived_on'] = str_replace(' ', '-', strtolower($data['name']));

        if ($category->update(Arr::only($data, $this->keys))) {
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
