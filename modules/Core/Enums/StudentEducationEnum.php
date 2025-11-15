<?php

namespace Modules\Core\Enums;

enum StudentEducationEnum: int
{
    case PAUD = 1;
    case TK = 2;
    case SD = 3;
    case SMP = 4;
    case SMA = 5;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::PAUD => 'PAUD',
            self::TK => 'TK',
            self::SD => 'SD',
            self::SMP => 'SMP',
            self::SMA => 'SMA',
        };
    }
}
