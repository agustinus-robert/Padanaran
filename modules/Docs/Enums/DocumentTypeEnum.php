<?php

namespace Modules\Docs\Enums;

use Modules\HRMS\Models\Employee;

enum DocumentTypeEnum: int
{
    case SYSTEM = 1;
    case GUIDE = 2;
    case NOTE = 3;
    case COMPANY = 4;
    case SPECIAL = 5;
    case TAX = 6;
    case IT = 7;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::SYSTEM => 'Sistem',
            self::GUIDE => 'Panduan',
            self::NOTE => 'Notula',
            self::COMPANY => 'Perusahaan',
            self::SPECIAL => 'Khusus',
            self::TAX => 'Formulir Pajak',
            self::IT => 'Dokumen IT'
        };
    }

    /**
     * Set the visibility
     */
    public function docVisibility(): bool
    {
        return match ($this) {
            self::SYSTEM, self::TAX, self::SPECIAL => false,
            self::GUIDE, self::NOTE, self::COMPANY, self::IT => true,
        };
    }

    /**
     * getVisibleOnly
     */
    public static function getVisibleOnly(): array
    {
        return array_values(array_filter(self::cases(), fn ($type) => $type->docVisibility()));
    }

    /**
     * Set the visibility
     */
    public function docApprovable(): bool
    {
        return match ($this) {
            self::SYSTEM, self::TAX, self::IT => false,
            self::GUIDE, self::NOTE, self::COMPANY, self::SPECIAL => true,
        };
    }

    /**
     * Set the visibility
     */
    public function docRecipient()
    {
        return match ($this) {
            self::SYSTEM, self::TAX => false,
            self::GUIDE, self::NOTE, self::COMPANY => Employee::isActive()->with('user')->get(),
            self::SPECIAL => Employee::isActive()->with('user')->whereHas('position', fn ($position) => $position->whereHas('position', fn ($level) => $level->whereIn('level', [3, 4])))->get(),
            self::IT => Employee::with('user')->whereIn('id', [27, 46, 53])->get(),
        };
    }
}
