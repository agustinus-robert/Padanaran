<?php

namespace Modules\Admin\Enums;

enum LandConditionEnum: int
{
    case Kurang   = 1;
    case Biasa     = 2;
    case Baik = 3;
    case SangatBaik = 4;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::Kurang   => 'Kurang',
            self::Biasa   => 'Biasa',
            self::Baik   => 'Baik',
            self::SangatBaik   => 'Sangat Baik',
        };
    }
}
