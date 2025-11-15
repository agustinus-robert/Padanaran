<?php

namespace Modules\Poz\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\Tax;

trait TaxRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'code',
        'name',
        'rate',
        'sale_active',
        'actived_on'
    ];

    /**
     * Store newly created resource.
     */
    public function storeTax(array $data)
    {
        $data['sale_active'] = 0;
        $tax = new Tax(Arr::only($data, $this->keys));
        if ($tax->save()) {
            $outletId = $data['outlet'];

            if ($outletId) {
                $tax->outlets()->attach($outletId);
            }

            return true;
        }

        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateTax(array $data, $id)
    {
        $data['sale_active'] = 0;
        $tax = Tax::find($id);
        if ($tax->update(Arr::only($data, $this->keys))) {
            $outletId = $data['outlet'];

            if ($outletId) {
                $tax->outlets()->syncWithoutDetaching($outletId);
            }

            return true;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyTax($id)
    {
        if (Tax::where('id', $id)->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreTax($id)
    {
        if (Tax::onlyTrashed()->find($id)->restore()) {
            return true;
        }
        return false;
    }
}
