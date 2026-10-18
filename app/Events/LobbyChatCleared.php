<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LobbyChatCleared implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;
    public string $time;

    public function __construct(string $message = 'Canal de subred purgado por el Administrador.')
    {
        $this->message = $message;
        $this->time    = now()->format('H:i');
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('robco-lobby-chat'),
            new Channel('robco-lobby'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'LobbyChatCleared';
    }
}
