<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateAreasAndDistricts
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public bool $forceUpdate;

    /**
     * Create a new event instance.
     */
    public function __construct(bool $forceUpdate = false)
    {
        $this->forceUpdate = $forceUpdate;
    }
}