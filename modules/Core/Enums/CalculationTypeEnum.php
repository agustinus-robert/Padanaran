<?php

namespace Modules\Core\Enums;

enum CalculationTypeEnum: string
{
    case MULTIPLIER = '*';
    case DIVIDER = '/';
    case ADDER = '+';
    case DEDUCTION = '-';

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::MULTIPLIER => 'Pengali',
            self::DIVIDER => 'Pembagi',
            self::ADDER => 'Penambah',
            self::DEDUCTION => 'Pengurang',
        };
    }

    /**
     * Get the html accessor with html() object
     */
    public function html($field): string
    {
        return match ($this) {
            self::MULTIPLIER => '*',
            self::DIVIDER => '/',
            self::ADDER => '+',
            self::DEDUCTION => '-',
        };
    }
}
