<?php

namespace Modules\Admin\Enums;

enum LandConcreteEnum: int
{
    case Beton   = 1;
    case TidakBeton = 2;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::Beton   => 'Beton',
            self::TidakBeton => 'Tidak Beton'
        };
    }
}
