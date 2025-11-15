<?php

namespace Modules\Core\Enums;

enum InventoryRegistrationTypeEnum: int
{
    case PMD = 1;
    case TTC = 2;
    case LGZ = 3;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::PMD => 'PT PéMad International Transearch',
            self::TTC => 'PéMad Translation Training Center',
            self::LGZ => 'Linguwiz'
        };
    }

    /**
     * Select enumerations with given case
     */
    public static function select($case)
    {
        return match ($case) {
            'PMD' => self::PMD,
            'TTC' => self::TTC,
            'LGZ' => self::LGZ,
            default => abort(404)
        };
    }

    public function code(): string
    {
        return match ($this) {
            self::PMD => 'PMD',
            self::TTC => 'TTC',
            self::LGZ => 'LGZ'
        };
    }
}
