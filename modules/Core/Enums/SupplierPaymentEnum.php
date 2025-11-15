<?php

namespace Modules\Core\Enums;

enum SupplierPaymentEnum: int
{
    case HARIAN = 1;
    case MINGGUNAN = 2;
    case TAHUNAN = 3;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::HARIAN => 'PAUD',
            self::MINGGUNAN => 'TK',
            self::TAHUNAN => 'SD'
        };
    }
}
