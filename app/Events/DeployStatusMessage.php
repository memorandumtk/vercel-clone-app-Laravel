<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeployStatusMessage implements ShouldBroadcast
{
    use SerializesModels;

    public string | null $message = null;
    public string $id;
    /**
     * Create a new event instance.
     */
    public function __construct(string $message, string $id)
    {
        Log::info("$id: $message");
        $this->message = $message;
        $this->id = $id;
    }


    public  function broadcastOn(): Channel
    {
        return new Channel('deploy-status');
    }
    public function broadcastWith(): array
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
        ];
    }
}
