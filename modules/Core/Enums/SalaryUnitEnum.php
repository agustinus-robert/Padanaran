<?php

namespace Modules\Core\Enums;

enum SalaryUnitEnum: int
{
    case IDR = 1;
    case USD = 2;
    case DAY = 3;
    case HOUR = 4;
    case PRESENCE = 5;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::IDR => 'Rupiah',
            self::USD => 'US Dollar',
            self::DAY => 'Hari',
            self::HOUR => 'Jam',
            self::PRESENCE => 'Kehadiran'
        };
    }

    /**
     * Get the prefix accessor with prefix() object
     */
    public function prefix(): string
    {
        return match ($this) {
            self::IDR => 'Rp',
            self::USD => '$',
            self::DAY => '',
            self::HOUR => '',
            self::PRESENCE => ''
        };
    }

    /**
     * Get the suffix accessor with suffix() object
     */
    public function suffix(): string
    {
        return match ($this) {
            self::IDR => '',
            self::USD => '',
            self::DAY => 'hari',
            self::HOUR => 'jam',
            self::PRESENCE => 'kehadiran'
        };
    }

    /**
     * Get the disabledState accessor with disabledState() object
     */
    public function disabledState(): string
    {
        return match ($this) {
            self::IDR => false,
            self::USD => false,
            self::DAY => true,
            self::HOUR => true,
            self::PRESENCE => true
        };
    }
}
