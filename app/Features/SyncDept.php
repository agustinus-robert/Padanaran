<?php

namespace App\Features;

use Illuminate\Support\Lottery;

class SyncDept
{
    /**
     * The stored name of the feature.
     *
     * @var string
     */
    public $name = 'sync-dept';

    /**
     * Resolve the feature's initial value.
     */
    public function resolve(mixed $scope): mixed
    {
        return false;
    }
}
