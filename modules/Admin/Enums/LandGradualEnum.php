<?php

namespace Modules\Admin\Enums;

enum LandGradualEnum: int
{
    case Bertingkat   = 1;
    case TidakBertingkat = 2;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::Bertingkat   => 'Bertingkat',
            self::TidakBertingkat => 'Tidak Bertingkat'
        };
    }
}
