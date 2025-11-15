<?php

namespace Modules\HRMS\Enums;

enum AppreciationTypeEnum: int
{
    case POM = 1;
    case OTHER = 2;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::POM => 'Personnel of the month',
            self::OTHER => 'Penghargaan lainnya'
        };
    }

    public function getRate(): string
    {
        return match ($this) {
            self::POM => 250000,
            self::OTHER => 0
        };
    }
}
