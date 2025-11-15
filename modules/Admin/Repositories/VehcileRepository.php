<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Models\Vehcile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

trait VehcileRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'brand',
        'code',
        'code_unit',
        'code_sub',
        'code_goods',
        'type',
        'cc',
        'acquisition_cost',
        'material',
        'acquisition_year',
        'qty',
        'number_chassis',
        'number_machine',
        'number_police',
        'bpkb',
        'color',
        'tax_date',
        'product_year',
        'identity_number',
        'assurance',
        'information',
        'address_primary',
        'conditional',
        'right_of_user',
        'pricings_lend'
    ];

    /**
     * Store newly created resource.
     */
    public function storeVehcile(array $data)
    {
        try {
            DB::beginTransaction();

            $data['acquisition_cost'] = str_replace('.','',$data['acquisition_cost']);
            $data['pricings_lend'] = str_replace('.','',$data['pricings_lend']);
            $vehcile = new Vehcile(Arr::only($data, $this->keys));
            
            if ($vehcile->save()){
                DB::commit();
                return true;
            }

        } catch (\PDOException $e) {
            // Woopsy
            dd($e);
            DB::rollBack();
            return false;
        }
    }

    /**
     * Update the current resource.
     */
    public function updateVehcile(array $data, $vehcile_id)
    {
        try {
            DB::beginTransaction();

            $data['acquisition_cost'] = str_replace('.','',$data['acquisition_cost']);
            $data['pricings_lend'] = str_replace('.','',$data['pricings_lend']);
            $vehcile = Vehcile::find($vehcile_id);

            if ($vehcile->update(Arr::only($data, $this->keys))) {
               DB::commit();
               return true;
            }

        } catch (\PDOException $e) {
            // Woopsy
            dd($e);
            DB::rollBack();
            return false;
        }
    }

    /**
     * Remove the current resource.
     */
    public function destroyVehcile($id)
    {
        if (Vehcile::where('id', $id)->delete()) {

            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreVehcile($id)
    {
        if (Vehcile::onlyTrashed()->find($id)->restore()) {
            
            return true;
        }
        return false;
    }
}
