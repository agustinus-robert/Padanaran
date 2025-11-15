<?php

namespace Modules\Core\Enums;

enum InventoryTypeEnum: int
{
    case GAINV    = 1;
    case ITINV    = 2;
    case NONGAINV = 3;
    case NONITINV = 4;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::ITINV    => 'Inventaris IT',
            self::GAINV    => 'Inventaris GA',
            self::NONGAINV => 'Tidak masuk inventaris IT',
            self::NONITINV => 'Tidak masuk inventaris GA',
        };
    }
}
