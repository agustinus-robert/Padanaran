<?php

namespace Modules\Admin\Enums;

enum BuildingsLandsEnum: int
{
    case Bangunan = 1;
    case Tanah = 2;
    case BangunanTanah = 3;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::Bangunan   => 'Hanya bangunan',
            self::Tanah => 'Hanya tanah',
            self::BangunanTanah => 'Bangunan dan Tanah'
        };
    }
}
