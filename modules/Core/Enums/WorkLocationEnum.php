<?php

namespace Modules\Core\Enums;

enum WorkLocationEnum: int
{
    case WFO = 1;
    case WFA = 2;
    // case WFOT = 3;
    // case WFAT = 4;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::WFO => 'Work from Office',
            self::WFA => 'Work from Anywhere',
            // self::WFOT => 'Work from Office (Teacher)',
            // self::WFAT => 'Work from Anywhere (Teacher)'
        };
    }

    /**
     * Select enumerations with given case
     */
    public static function select($case)
    {
        return match ($case) {
            'WFO' => self::WFO,
            'WFA' => self::WFA,
                // 'WFOT' => self::WFOT,
                // 'WFAT' => self::WFAT,
            default => abort(404)
        };
    }
}
