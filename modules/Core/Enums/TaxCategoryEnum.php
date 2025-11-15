<?php

namespace Modules\Core\Enums;

enum TaxCategoryEnum: int
{
    case LAYER1 = 1;
    case LAYER2 = 2;
    case LAYER3 = 3;
    case LAYER4 = 4;
    case LAYER5 = 5;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::LAYER1 => 'PPh lapis pertama (5%)',
            self::LAYER2 => 'PPh lapis kedua (15%)',
            self::LAYER3 => 'PPh lapis ketiga (25%)',
            self::LAYER4 => 'PPh lapis keempat (30%)',
            self::LAYER5 => 'PPh lapis kelima (35%)',
        };
    }

    public function labelnonnpwp(): string
    {
        return match ($this) {
            self::LAYER1 => 'PPh lapis pertama non NPWP (6%)',
            self::LAYER2 => 'PPh lapis kedua non NPWP (18%)',
            self::LAYER3 => 'PPh lapis ketiga non NPWP (30%)',
            self::LAYER4 => 'PPh lapis keempat non NPWP (36%)',
            self::LAYER5 => 'PPh lapis kelima non NPWP (42%)',
        };
    }

    public function getMin(): int
    {
        return match ($this) {
            self::LAYER1 => 0,
            self::LAYER2 => 60000000,
            self::LAYER3 => 250000000,
            self::LAYER4 => 500000000,
            self::LAYER5 => 5000000000,
        };
    }

    public function getMax(): int
    {
        return match ($this) {
            self::LAYER1 => 60000000,
            self::LAYER2 => 250000000,
            self::LAYER3 => 500000000,
            self::LAYER4 => 5000000000,
            self::LAYER5 => 999999999999999999,
        };
    }

    public function getPercentage(): int
    {
        return match ($this) {
            self::LAYER1 => 5,
            self::LAYER2 => 15,
            self::LAYER3 => 25,
            self::LAYER4 => 30,
            self::LAYER5 => 35,
        };
    }

    public function getPercentageNonNpwp(): int
    {
        return match ($this) {
            self::LAYER1 => 6,
            self::LAYER2 => 18,
            self::LAYER3 => 30,
            self::LAYER4 => 36,
            self::LAYER5 => 42,
        };
    }

    public function getSubstraction(): int
    {
        return match ($this) {
            self::LAYER1 => 0,
            self::LAYER2 => 60000000,
            self::LAYER3 => 310000000,
            self::LAYER4 => 810000000,
            self::LAYER5 => 5810000000,
        };
    }
}
