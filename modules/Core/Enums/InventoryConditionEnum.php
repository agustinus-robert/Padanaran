<?php

namespace Modules\Core\Enums;

enum InventoryConditionEnum: int
{
    case GOOD       = 1;
    case DAMAGED    = 2;
    case UNSET      = 3;
    case EXPIRED    = 4;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::GOOD      => 'Baik',
            self::DAMAGED   => 'Rusak',
            self::UNSET     => 'Tidak diketahui',
            self::EXPIRED   => 'Habis masa pakai',
        };
    }

    /**
     * Get the color accessor with color() object
     */
    public function color(): string
    {
        return match ($this) {
            self::GOOD      => 'success',
            self::DAMAGED   => 'danger',
            self::UNSET     => 'warning',
            self::EXPIRED   => 'secondary',
        };
    }

    /**
     * Get the icon accessor with icon() object
     */
    public function icon(): string
    {
        return match ($this) {
            self::GOOD      => 'mdi mdi-check-circle-outline',
            self::DAMAGED   => 'mdi mdi-close-circle-outline',
            self::UNSET     => 'mdi mdi-timer-outline',
            self::EXPIRED   => 'mdi mdi-sort-clock-ascending-outline',
        };
    }

    /**
     * Try from name
     */
    public static function tryFromName(string $name): ?static
    {
        foreach (static::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        return null;
    }
}
