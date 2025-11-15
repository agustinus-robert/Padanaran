<?php

namespace Modules\Core\Repositories;

use Arr;
use Auth;
use Modules\Core\Models\CompanyMoment;

trait CompanyMomentRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'type', 'date', 'name', 'is_holiday'
    ];

    /**
     * Store newly created resource.
     */
    public function storeCompanyMoment(array $data)
    {
        $moment = new CompanyMoment(Arr::only($data, $this->keys));
        if($moment->save()) {
            Auth::user()->log('membuat hari libur baru dengan nama '.$moment->name.' <strong>[ID: '.$moment->id.']</strong>', CompanyMoment::class, $moment->id);
            return $moment;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanyMoment(CompanyMoment $moment, array $data)
    {        
        $moment = $moment->fill(Arr::only($data, $this->keys));
        if($moment->save()) {
            Auth::user()->log('memperbarui hari libur '.$moment->name.' <strong>[ID: '.$moment->id.']</strong>', CompanyMoment::class, $moment->id);
            return $moment;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanyMoment(CompanyMoment $moment)
    {
        if($moment->delete()) {
            Auth::user()->log('menghapus permanen hari libur '.$moment->name.' <strong>[ID: '.$moment->id.']</strong>', CompanyMoment::class, $moment->id);
            return $moment;
        }
        return false;
    }
}