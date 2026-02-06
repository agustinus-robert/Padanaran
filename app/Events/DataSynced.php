<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; 
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DataSynced implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payload; 

    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function broadcastOn()
    {
        return new Channel('sync-channel');
    }

    // Nama event di JS akan menjadi 'DataSynced'
    public function broadcastAs()
    {
        return 'DataSynced';
    }
}