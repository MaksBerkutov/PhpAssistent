<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScenarioTriggered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $scenarioKey;
    public array $payload;

    public function __construct(string $scenarioKey, array $payload = [])
    {
        $this->scenarioKey = $scenarioKey;
        $this->payload = $payload;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
