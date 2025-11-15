<?php

namespace Modules\HRMS\Enums;

enum TaxTypeEnum: int
{
    case MONTHLY = 1;
    case YEARLY = 2;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::MONTHLY => 'PPh 21 perbulan',
            self::YEARLY => 'PPh 21 pertahun'
        };
    }
}
