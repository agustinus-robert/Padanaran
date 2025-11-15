<?php

namespace Modules\Admin\Http\Livewire;

trait ForwardsAttributes
{
    public $attributez;

    public function mount(...$attrs)
    {
        $this->mapAttributez(...$attrs);
    }

    public function mapAttributez(...$attrs)
    {
        $attributez = '';
        collect(...$attrs)->each(function ($value, $attr) use (&$attributez) {
            $attributez .= " {$attr}=\"{$value}\"";
        });
        $this->attributez = $attributez;
    }
}