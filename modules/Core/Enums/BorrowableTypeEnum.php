<?php

namespace Modules\Core\Enums;

use Modules\Core\Models\CompanyBuildingRoom;
use Modules\Core\Models\CompanyInventory;

enum BorrowableTypeEnum: int
{
    case ROOM = 1;
    case INVENTORY = 2;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::ROOM => 'Ruang',
            self::INVENTORY => 'Inventaris'
        };
    }

    /**
     * Get the instance accessor with instance() object
     */
    public function instance(): string
    {
        return match ($this) {
            self::ROOM => CompanyBuildingRoom::class,
            self::INVENTORY => CompanyInventory::class,
        };
    }

    /**
     * Get the conditions accessor with conditions() object
     */
    public function conditions(): array
    {
        return match ($this) {
            self::ROOM => [],
            self::INVENTORY => []
        };
    }

    /**
     * Get the relations accessor with relations() object
     */
    public function relations()
    {
        return match ($this) {
            self::ROOM => [],
            self::INVENTORY => []
        };
    }

    /**
     * Get the max number on asset
     */
    public function maxnumber()
    {
        return match ($this) {
            self::ROOM => CompanyBuildingRoom::max('id'),
            self::INVENTORY => CompanyInventory::whereNotNull('bought_at')->max('id')
        };
    }

    /**
     * Get the max number on asset
     */
    public function invnum()
    {
        return match ($this) {
            self::ROOM => '',
            self::INVENTORY => ''
        };
    }

    /**
     * Get the getter accessor with getter() object
     */
    public function getter(): string
    {
        return match ($this) {
            self::ROOM => 'name',
            self::INVENTORY => 'name'
        };
    }

    /**
     * Get the multiple items
     */
    public function multiples(): bool
    {
        return match ($this) {
            self::ROOM => false,
            self::INVENTORY => true
        };
    }

    /**
     * Get the accessor with approver() object
     */
    public function approver(): array
    {
        return match ($this) {
            self::ROOM => config('modules.asset.features.lease.approver_arrays.room.value'),
            self::INVENTORY => config('modules.asset.features.lease.approver_arrays.devices.value')
        };
    }

    /**
     * Get the accessor with description() object
     */
    public function description(): string
    {
        return match ($this) {
            self::ROOM => 'Silakan klik menu ini untuk mengajukan pinjaman ruang',
            self::INVENTORY => 'Silakan klik menu ini untuk mengajukan pinjaman perangkat'
        };
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
