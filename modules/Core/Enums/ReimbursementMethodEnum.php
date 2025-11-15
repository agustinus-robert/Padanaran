<?php

namespace Modules\Core\Enums;

enum ReimbursementMethodEnum: int
{
    case PAYROLL = 1;
    case DIRECT = 2;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::PAYROLL => 'Bersama gaji',
            self::DIRECT => 'Langsung',
        };
    }

    /**
     * Get the getPaidOfAtValue accessor with getPaidOfAtValue() object
     */
    public function getPaidOfAtValue(): string|null
    {
        return match ($this) {
            self::PAYROLL => null,
            self::DIRECT => now(),
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

    /**
     * Get the file accessor with file() object
     */
    public function hasFile(): string
    {
        return match ($this) {
            self::PAYROLL => false,
            self::DIRECT => true,
        };
    }

    /**
     * Get the defaultPaidAt accessor with defaultPaidAt() object
     */
    public function defaultPaidAt(): string
    {
        return match ($this) {
            self::PAYROLL => cmp_cutoff(1)->format('Y-m-d 09:00:00'),
            self::DIRECT => date('Y-m-d H:i:s'),
        };
    }
}
