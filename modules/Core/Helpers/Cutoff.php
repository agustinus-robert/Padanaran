<?php

use Carbon\Carbon;

if (!function_exists('cutoff')) {
    function cmp_cutoff($index)
    {
        return Carbon::parse(
            array_reduce(setting('cmp_cutoff_date')[$index] ?? [], fn($result, $time) => strtotime($time, $result ?? null))
        );
    }
}
