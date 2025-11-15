<?php

namespace App\Features;

use Illuminate\Support\Lottery;

class SyncPoss
{
    /**
     * The stored name of the feature.
     *
     * @var string
     */
    public $name = 'sync-poss';

    /**
     * Resolve the feature's initial value.
     */
    public function resolve(mixed $scope): mixed
    {
        return false;
    }
}
