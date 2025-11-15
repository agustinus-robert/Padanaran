<?php

namespace Modules\Core\Enums;

enum LoanTypeEnum: int
{
    case LOAN = 1;
    case INTEREST = 2;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::LOAN => 'Pinjaman',
            self::INTEREST => 'Bunga'
        };
    }

    public function meta(): array
    {
        return match ($this) {
            self::LOAN => [
                'az',
                'visible',
                'tenor',
                'interest',
                'file',
                'divider',
                'only_permanent_empl',
                'multiplied_by_tenor',
            ],
            self::INTEREST => [
                'az',
                'visible',
                'tenor',
                'interest',
                'file',
                'divider',
                'only_permanent_empl',
                'multiplied_by_tenor',
            ]
        };
    }
}
