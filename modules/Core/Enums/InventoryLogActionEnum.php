<?php

namespace Modules\Core\Enums;

use Modules\Core\Models\CompanyBorrowItem;
use Modules\Core\Models\CompanyInventory;

enum InventoryLogActionEnum: int
{
    case BUY       = 1;
    case RESTOCK   = 2;
    case REFILL    = 3;
    case REPAIR    = 4;
    case REFURBISH = 5;
    case MISSING   = 6;
    case SELL      = 7;
    case DISPOSE   = 8;
    case OTHER     = 9;
    case LOAN      = 10;
    case RETURN    = 11;
    case MAINTENANCE = 12;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::BUY       => 'Beli baru',
            self::RESTOCK   => 'Restok',
            self::REFILL    => 'Refill/diisi ulang',
            self::REPAIR    => 'Diperbaiki',
            self::REFURBISH => 'Diperbarui',
            self::MISSING   => 'Hilang',
            self::SELL      => 'Dijual',
            self::DISPOSE   => 'Dihapus/dihancurkan',
            self::OTHER     => 'Lainnya',
            self::LOAN      => 'Peminjaman',
            self::RETURN    => 'Pengembalian',
            self::MAINTENANCE => 'perbaikan'
        };
    }

    /**
     * Get the color accessor with color() object
     */
    public function color(): string
    {
        return match ($this) {
            self::BUY, self::RESTOCK, self::REFILL, self::RETURN => 'success',
            self::REPAIR, self::REFURBISH, self::MAINTENANCE => 'primary',
            self::MISSING, self::SELL, self::DISPOSE, self::LOAN => 'danger',
            self::OTHER => 'warning',
        };
    }

    /**
     * Get the icon accessor with icon() object
     */
    public function icon(): string
    {
        return match ($this) {
            self::BUY       => 'mdi mdi-timer-outline',
            self::RESTOCK   => 'mdi mdi-check-circle-outline',
            self::REFILL    => 'mdi mdi-check-circle-outline',
            self::REPAIR    => 'mdi mdi-alert-circle-outline',
            self::REFURBISH => 'mdi mdi-alert-circle-outline',
            self::MISSING   => 'mdi mdi-close-circle-outline',
            self::SELL      => 'mdi mdi-close-circle-outline',
            self::DISPOSE   => 'mdi mdi-close-circle-outline',
            self::OTHER     => 'mdi mdi-close-circle-outline',
            self::LOAN      => 'mdi mdi-close-circle-outline',
            self::RETURN    => 'mdi mdi-close-circle-outline'
        };
    }

    /**
     * Get the message accessor with message() object
     */
    public function message(): string
    {
        return match ($this) {
            self::BUY       => ' membeli barang baru untuk ',
            self::RESTOCK   => ' membeli persediaan baru untuk ',
            self::REFILL    => ' mengisi ulang ',
            self::REPAIR    => ' memperbaiki ',
            self::REFURBISH => ' mendaurulang ',
            self::MISSING   => ' menghilangkan ',
            self::SELL      => ' menjual ',
            self::DISPOSE   => ' menghapus/menghancurkan ',
            self::OTHER     => ' melakukan tindakan lainnya untuk ',
            self::LOAN      => ' meminjamkan ',
            self::RETURN    => ' menerima pengembalian '
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
     * Get the all data
     */
    public function getData()
    {
        return match ($this) {
            self::BUY => CompanyInventory::whereNull('bought_at')->get(),
            self::REPAIR, self::REFURBISH, self::MISSING, self::SELL, self::DISPOSE, self::OTHER, self::LOAN => CompanyInventory::whereNotNull('bought_at')->get(),
            self::RETURN => CompanyBorrowItem::whereJsonContains('meta->onBorrow', '1')->get()
        };
    }
}
