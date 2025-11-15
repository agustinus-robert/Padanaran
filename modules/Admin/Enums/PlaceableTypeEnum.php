<?php

namespace Modules\Admin\Enums;

use Modules\Admin\Models\Building;
use Modules\Admin\Models\BuildingRoom;
use Modules\Account\Models\Employee;

enum PlaceableTypeEnum: int
{
    case BUILDING = 1;
    case ROOM = 2;
    case EMPLOYEE = 3;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::BUILDING => 'Gedung',
            self::ROOM => 'Ruang',
            self::EMPLOYEE => 'Karyawan'
        };
    }

    /**
     * Get the instance accessor with instance() object
     */
    public function instance(): string
    {
        return match ($this) {
            self::BUILDING => Building::class,
            self::ROOM => BuildingRoom::class,
            self::EMPLOYEE => Employee::class
        };
    }

    /**
     * Get the conditions accessor with conditions() object
     */
    public function conditions(): array
    {
        return match ($this) {
            self::BUILDING => [],
            self::ROOM => [],
            self::EMPLOYEE => []
        };
    }

    /**
     * Get the relations accessor with relations() object
     */
    public function relations()
    {
        return match ($this) {
            self::BUILDING => [],
            self::ROOM => [],
            self::EMPLOYEE => 'user'
        };
    }

    /**
     * Get the relations accessor with relations() object
     */
    public function order()
    {
        return match ($this) {
            self::BUILDING => 'name',
            self::ROOM => 'name',
            self::EMPLOYEE => 'id'
        };
    }

    /**
     * Get the getter accessor with getter() object
     */
    public function getter(): string
    {
        return match ($this) {
            self::BUILDING => 'name',
            self::ROOM => 'name',
            self::EMPLOYEE => 'user.name'
        };
    }

    /**
     * Get the conditions accessor with getUserId() object
     */
    public function getUserId($id): int|null
    {
        $object = match ($this) {
            self::BUILDING => null,
            self::ROOM => null,
            self::EMPLOYEE => 'user.id'
        };

        return is_null($object) ? null : data_get((new ($this->instance()))->find($id), $object);
    }

    /**
     * Try from instance
     */
    public static function tryFromInstance(string $instance): ?static
    {
        foreach (static::cases() as $case) {
            if ($case->instance() === $instance) {
                return $case;
            }
        }

        return null;
    }

    /**
     * Force ry from integer or instance
     */
    public static function forceTryFrom($type): ?static
    {
        return self::tryFrom((int) $type) ?: self::tryFromInstance($type);
    }
}
