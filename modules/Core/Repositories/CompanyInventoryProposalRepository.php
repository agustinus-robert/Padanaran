<?php

namespace Modules\Core\Repositories;

use Arr;
use Auth;
use Modules\Core\Enums\InventoryConditionEnum;
use Modules\Core\Models\CompanyInventory;
use Modules\Core\Models\CompanyInventoryProposal;
use Modules\Core\Repositories\CompanyInventoryRepository;

trait CompanyInventoryProposalRepository
{
    use CompanyInventoryRepository;

    /**
     * Store newly created resource.
     */
    public function storeCompanyInventoryProposal(array $data)
    {
        $proposal = new CompanyInventoryProposal(Arr::only($data, ['user_id', 'description', 'name', 'meta']));
        if ($proposal->save()) {
            $proposal->items()->createMany($data['items']);
            Auth::user()->log('membuat proposal baru ' . $proposal->name . ' <strong>[ID: ' . $proposal->id . ']</strong>', CompanyInventoryProposal::class, $proposal->id);
            return $proposal;
        }
        return false;
    }

    /**
     * Update the current resource.
     */
    public function updateCompanyInventoryProposal(CompanyInventoryProposal $proposal, array $data)
    {
        if ($proposal->fill(Arr::only($data, ['description', 'name', 'meta']))->save()) {
            $itemIds = collect($data['items'])->map(fn ($item) => CompanyInventory::updateOrCreate(
                ['kd' => $item['kd']],
                [
                    'name'      => $item['name'],
                    'category'  => $item['category'],
                    'placeable_type' => $item['placeable_type'],
                    'placeable_id'   => $item['placeable_id'],
                    'bought_price'  => $item['bought_price'],
                    'quantity' => $item['quantity'],
                    'proposal_id' => $proposal->id,
                ]
            ));
            Auth::user()->log('memperbarui proposal ' . $proposal->name . ' <strong>[ID: ' . $proposal->id . ']</strong> dengan jumlah barang ' . count($itemIds), CompanyInventoryProposal::class, $proposal->id);
            return $proposal;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyCompanyInventoryProposal(CompanyInventoryProposal $proposal)
    {
        if (!$proposal->trashed() && $proposal->delete()) {
            Auth::user()->log('menghapus proposal ' . $proposal->name . ' <strong>[ID: ' . $proposal->id . ']</strong>', CompanyInventoryProposal::class, $proposal->id);
            return $proposal;
        }
        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreCompanyInventoryProposal(CompanyInventoryProposal $proposal)
    {
        if ($proposal->trashed() && $proposal->restore()) {
            Auth::user()->log('memulihkan proposal ' . $proposal->name . ' <strong>[ID: ' . $proposal->id . ']</strong>', CompanyInventoryProposal::class, $proposal->id);
            return $proposal;
        }
        return false;
    }
}
