<?php

namespace Modules\Core\Enums;

enum QuestionTypeEnum: int
{
    case TEXT     = 1;
    case SELECT   = 2;
    case TEXTAREA = 3;
    case RADIO    = 4;
    case CHECKBOX = 5;


    /**
     * Get the label accessor with label() object
     */
    public function label(): string
    {
        return match ($this) {
            self::TEXT     => 'Jawaban singkat',
            self::SELECT   => 'Daftar pilihan',
            self::TEXTAREA => 'Paragraf',
            self::RADIO    => 'Pilihan ganda',
            self::CHECKBOX => 'Kotak centang',
        };
    }

    /**
     * Get the label accessor with label() object
     */
    public function render(): string
    {
        return match ($this) {
            self::TEXT     => '<input type="text" class="form-control" id="answer" name="answer">',
            self::SELECT   => '<input type="text" class="form-control col-md-6" id="answer" name="answer[]"><a href="javascript:;" class="btn btn-soft-primary col-md-1"><i class="mdi mdi-plus"></i></a>',
            self::TEXTAREA => '<textarea name="answer" id="answer" class="form-control"></textarea>',
            self::RADIO    => '<input type="text" class="form-control" id="answer" name="answer[]">',
            self::CHECKBOX => '<input type="text" class="form-control" id="answer" name="answer[]">',
        };
    }
}
