<?php

namespace Modules\Admin\Models\Traits;

use Modules\Core\Enums\PlaceableTypeEnum;
use Modules\Core\Enums\InventoryConditionEnum;
use Modules\Core\Enums\InventoryLogActionEnum;

trait InventoryControls
{
    /**
     * scope is owned
     * */
    public function scopeIsOwned($query)
    {
        return $query->whereNotNull('bought_at')->whereNull('sold_at');
    }

    /**
     * scope is sold
     * */
    public function scopeIsSold($query)
    {
        return $query->whereNotNull('sold_at');
    }

    /**
     * scope is broken item
     * */
    public function scopeIsBroken($query)
    {
        return $query->whereNotNull('bought_at')
            ->whereNotNull('sold_at')
            ->whereDoesntHave('logs', fn ($item) => $item->whereAction(InventoryLogActionEnum::SELL))
            ->whereCondition(InventoryConditionEnum::DAMAGED);
    }

    /**
     * scope is sold
     * */
    public function scopeIsType($query, $type)
    {
        return $query->when($type, fn ($q) => $q->whereType($type));
    }

    /**
     * Find by Employee location.
     */
    public function scopeFindByEmployeeLocation($query, $location)
    {
        return $query->when($location, fn ($q) => $q->whereHas('user.employee', fn ($empl) => $empl->whereHas('contract', fn ($ctr) => $ctr->where('work_location', $location))));
    }

    /**
     * Find by inv number.
     */
    public function scopeFindInvNum($query, $inv)
    {
        return $query->when($inv, fn ($subQuery) => $subQuery->where('meta->inv_num', $inv));
    }

    /**
     * Find by kd.
     */
    public function scopeFindByKd($query, $kd)
    {
        return $query->where('kd', $kd)->firstOrFail();
    }

    /**
     * Find by inv number.
     */
    public function scopeSearchCategory($query, $category)
    {
        return $query->when($category, fn ($subQuery) => $subQuery->where('category', $category));
    }
}
