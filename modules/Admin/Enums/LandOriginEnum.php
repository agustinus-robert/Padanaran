<?php

namespace Modules\Admin\Enums;

enum LandOriginEnum: int
{
    case Pembelian   = 1;
    case Hibah = 2;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::Pembelian   => 'Pembelian',
            self::Hibah => 'Hibah'
        };
    }
}
