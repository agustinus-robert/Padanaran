<?php

namespace Modules\Core\Enums;

enum SupplierWorkEnum: int
{
    case MORNING = 1;
    case AFTERNOON = 2;
    case EVENING = 3;

    /**
     * Time period label: "Morning", "Afternoon", "Evening"
     */
    public function label(): string
    {
        return match ($this) {
            self::MORNING => 'Morning',
            self::AFTERNOON => 'Afternoon',
            self::EVENING => 'Evening',
        };
    }

    /**
     * Start time of the service period
     */
    public function startTime(): string
    {
        return match ($this) {
            self::MORNING => '06:00',
            self::AFTERNOON => '11:00',
            self::EVENING => '15:00',
        };
    }

    /**
     * End time of the service period
     */
    public function endTime(): string
    {
        return match ($this) {
            self::MORNING => '11:00',
            self::AFTERNOON => '15:00',
            self::EVENING => '18:00',
        };
    }

    /**
     * Service label: type of service provided
     */
    public function serviceLabel(): string
    {
        return match ($this) {
            self::MORNING => 'None',
            self::AFTERNOON => 'Meals',
            self::EVENING => 'Accommodation',
        };
    }

    public function labelIndo(): string
    {
        return match ($this) {
            self::MORNING => 'Pagi',
            self::AFTERNOON => 'Siang',
            self::EVENING => 'Sore',
        };
    }

    public static function fromKey(string $key): ?self
    {
        return match (strtolower($key)) {
            'morning' => self::MORNING,
            'afternoon' => self::AFTERNOON,
            'evening' => self::EVENING,
            default => null,
        };
    }
}
