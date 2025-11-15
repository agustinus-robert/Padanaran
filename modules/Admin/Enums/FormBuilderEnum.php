<?php

namespace Modules\Admin\Enums;

enum FormBuilderEnum: int
{
    case Text = 1;
    case TextArea = 2;
    case File = 3;
    case Number = 4;

    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::Text   => 'Tulisan',
            self::TextArea => 'Tulisan Panjang',
            self::File => 'File',
            self::Number => 'Nomor'
        };
    }
}
