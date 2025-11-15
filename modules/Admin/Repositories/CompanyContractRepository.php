<?php

namespace Modules\Admin\Repositories;

use App\Models\Contract;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

trait CompanyContractRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'kd', 'name', 'description'
    ];

    /**
     * Store newly created resource.
     */
    public function storeCompanyContract(array $data)
    {
        $contract = new Contract(Arr::only($data, $this->keys));
        if ($contract->save()) {
            Auth::user()->log('membuat kontrak baru ' . $contract->name . ' <strong>[ID: ' . $contract->id . ']</strong>', Contract::class, $contract->id);
            return $contract;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanyContract(Contract $contract, array $data)
    {
        $contract = $contract->fill(Arr::only($data, $this->keys));
        if ($contract->save()) {
            Auth::user()->log('memperbarui departemen ' . $contract->name . ' <strong>[ID: ' . $contract->id . ']</strong>', Contract::class, $contract->id);
            return $contract;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanyContract(Contract $contract)
    {
        if (!$contract->trashed() && $contract->delete()) {
            Auth::user()->log('menghapus departemen ' . $contract->name . ' <strong>[ID: ' . $contract->id . ']</strong>', Contract::class, $contract->id);
            return $contract;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanyContract(Contract $contract)
    {
        if ($contract->trashed() && $contract->restore()) {
            Auth::user()->log('memulihkan departemen ' . $contract->name . ' <strong>[ID: ' . $contract->id . ']</strong>', Contract::class, $contract->id);
            return $contract;
        }
        return false;
    }
}
